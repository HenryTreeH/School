<?php

namespace App\Commands;

use GuzzleHttp\Exception\GuzzleException;
use App\Utils;  // ✅ Corrected namespace
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Exception\RuntimeException;
use DOMDocument;
use DOMXPath;
use mysqli;

#[AsCommand(name: 'scrape-page')]
class ScrapePage extends Command
{
    protected function configure(): void
    {
        $this
            ->setDescription('ScrapePage overview page.')
            ->setHelp('This command allows you to request an overview page...')
            ->addArgument('domain', InputArgument::REQUIRED, 'What domain do you want to scrape?');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $domain = $input->getArgument('domain');
        $output->writeln("🔍 Scraping: <info>$domain</info>\n");

        // ✅ Locate the .ini file
        $configDir = "C:/xampp/htdocs/schoolcoursev2/ini";
        $iniFile = "{$configDir}/{$domain}.ini";

        if (!file_exists($iniFile)) {
            throw new RuntimeException("❌ Configuration file not found: {$iniFile}");
        }

        // ✅ Parse .ini file
        $scraperConfig = parse_ini_file($iniFile, true);
        if ($scraperConfig === false) {
            throw new RuntimeException("❌ Failed to parse INI file.");
        }

        // ✅ Display Configuration Summary
        $output->writeln("📜 <comment>Configuration Loaded</comment>\n" . str_repeat("-", 30));
        foreach ($scraperConfig as $section => $values) {
            $output->writeln("<info>[$section]</info>");
            foreach ($values as $key => $value) {
                $output->writeln("  - <comment>$key:</comment> $value");
            }
            $output->writeln(""); // Line break for readability
        }

        // ✅ Fetch Overview Page
        $overviewUrl = $scraperConfig['overview']['page'] ?? '';
        if (empty($overviewUrl)) {
            throw new RuntimeException("❌ Overview page URL not found in config.");
        }

        $output->writeln("🌍 <info>Requesting Overview Page:</info> $overviewUrl\n");
        $overviewPage = Utils::requestPage($overviewUrl);

        // ✅ Scrape Detail Page URLs
        $detailUrls = $this->scrapeDetailPageUrls($scraperConfig, $overviewPage);

        foreach ($detailUrls as $index => $url) {
            $output->writeln("📌 <info>[" . ($index + 1) . "] Fetching:</info> $url"); // ✅ Fixed array index
            $detailData = $this->fetchDetailPageData($scraperConfig, $url);

            // ✅ Display Property Details in a Clear Format
            $output->writeln("\n🏡 <fg=cyan;options=bold>Property Details</> " . str_repeat("-", 30));
            $output->writeln("<comment>Title:</comment> {$detailData['title_xpath']}");
            $output->writeln("<comment>Price:</comment> {$detailData['price_xpath']}");
            $output->writeln("<comment>Description:</comment> " . substr($detailData['description_xpath'], 0, 150) . "...");
            $output->writeln("<comment>Surface:</comment> {$detailData['surface_xpath']}");
            $output->writeln("<comment>Bedrooms:</comment> {$detailData['bedrooms_xpath']}");
            $output->writeln("<comment>Photo:</comment> {$detailData['fotos_xpath']}");
            $output->writeln(str_repeat("-", 40) . "\n");
        }

        return Command::SUCCESS;
    }

    /**
     * @throws GuzzleException
     */
    public function fetchOverviewPageData(array $scraperConfig): string
    {
        $overviewUrl = $scraperConfig['overview']['page'] ?? '';
        if (empty($overviewUrl)) {
            throw new RuntimeException('Overview URL not found in scraper config.');
        }

        return Utils::requestPage($overviewUrl);
    }

    public function scrapeDetailPageUrls(array $scraperConfig, string $overviewPage): array
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($overviewPage); // Suppress warnings for invalid HTML

        $xPath = new DOMXPath($dom);
        $detailXPath = $scraperConfig['details']['detail_xpath'] ?? '';
        if (empty($detailXPath)) {
            throw new RuntimeException('Detail XPath not found in scraper config.');
        }

        $nodeList = $xPath->query($detailXPath);

        $urls = [];
        foreach ($nodeList as $node) {
            $urls[] = $node->nodeValue;
        }

        return $urls;
    }

    public function fetchDetailPageData(array $scraperConfig, string $detailPageUrl): array
    {
        $prefix = $scraperConfig['details']['detail_prefix'] ?? '';
        $html = Utils::requestPage($detailPageUrl, '', $prefix);

        $dom = new DOMDocument();
        @$dom->loadHTML($html); // Suppress warnings for invalid HTML
        $xPath = new DOMXPath($dom);

        $data = [];
        foreach ($scraperConfig['details'] as $fieldName => $fieldSelector) {
            if (!empty($fieldSelector) && strpos($fieldName, '_xpath') !== false) {
                $nodes = $xPath->query($fieldSelector);

                if ($nodes && $nodes->length > 0) {
                    $value = trim($nodes->item(0)->textContent);
                    if ($fieldName === 'fotos_xpath') {
                        $fotosPrefix = $scraperConfig['details']['fotos_prefix'] ?? '';
                        $value = $fotosPrefix . $value;
                    }
                    $data[$fieldName] = $value;
                } else {
                    $data[$fieldName] = 'N/A';
                }
            }
        }

        return $data;
    }

    public function saveDetailPagesToDatabase(array $parsedDetailPages): void
    {
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'scraped_data';

        // ✅ Create connection with proper encoding
        $conn = new mysqli($servername, $username, $password, $dbname);
        $conn->set_charset("utf8mb4");

        // ✅ Check connection
        if ($conn->connect_error) {
            throw new RuntimeException('❌ Database connection failed: ' . $conn->connect_error);
        }

        // ✅ Check if table exists
        $checkTable = $conn->query("SHOW TABLES LIKE 'scraped_pages'");
        if ($checkTable->num_rows == 0) {
            throw new RuntimeException('❌ Table "scraped_pages" does not exist. Please create it first.');
        }

        // ✅ Prepare the SQL statement
        $stmt = $conn->prepare('INSERT INTO scraped_pages (title, price, description, surface, bedrooms, photo) VALUES (?, ?, ?, ?, ?, ?)');
        if (!$stmt) {
            throw new RuntimeException('❌ Prepare statement failed: ' . $conn->error);
        }

        // ✅ Loop through data and insert into database
        foreach ($parsedDetailPages as $page) {
            $title = $page['title_xpath'] ?? 'N/A';
            $price = $page['price_xpath'] ?? 'N/A';
            $description = $page['description_xpath'] ?? 'N/A';
            $surface = $page['surface_xpath'] ?? 'N/A';
            $bedrooms = $page['bedrooms_xpath'] ?? 'N/A';
            $photo = $page['fotos_xpath'] ?? 'N/A';

            // ✅ Debugging output
            echo "📌 Inserting: $title | $price | $description | $surface | $bedrooms | $photo\n";

            if (!$stmt->bind_param('ssssss', $title, $price, $description, $surface, $bedrooms, $photo)) {
                throw new RuntimeException('❌ Binding parameters failed: ' . $stmt->error);
            }

            if (!$stmt->execute()) {
                throw new RuntimeException('❌ Execution failed: ' . $stmt->error);
            }
        }

        // ✅ Cleanup
        $stmt->close();
        $conn->close();
        echo "✅ Successfully saved data to the database.\n";
    }
}
