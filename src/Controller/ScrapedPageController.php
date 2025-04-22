<?php

namespace App\Controller;

use App\Entity\ScrapedPage;
use App\Entity\ScrapeConfig;
use App\Entity\ScrapeLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScrapedPageController extends AbstractController
{
    #[Route('/scraped-result', name: 'app_scraped_pages')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Fetch recent logs (you can adjust the number or conditions)
        $recentLogs = $entityManager->getRepository(ScrapeLog::class)->findBy([], ['createdAt' => 'DESC'], 10); // Last 10 logs

        // Fetch statistics (based on your custom query)
        $stats = $entityManager->getRepository(ScrapeLog::class)->getScrapeStats();

        // Pass data to the template
        return $this->render('scraped_page/index.html.twig', [
            'scrapedPages' => $entityManager->getRepository(ScrapedPage::class)->findAll(),
            'configs' => $entityManager->getRepository(ScrapeConfig::class)->findAll(),
            'stats' => $stats,  // Statistics data
            'recentLogs' => $recentLogs,  // Recent logs data
        ]);
    }

    #[Route('/scrape/run', name: 'app_scrape_run')]
    public function runScrape(EntityManagerInterface $em): Response
    {
        // Create a new scrape log
        $scrapeLog = new ScrapeLog();
        $scrapeLog->setUser($this->getUser());
        $scrapeLog->setScraperName('ExampleScraper');
        $scrapeLog->setStatus('started');
        $scrapeLog->setCreatedAt(new \DateTime());

        // Persist and flush the log to generate an ID
        $em->persist($scrapeLog);
        $em->flush();

        // Get the generated log ID
        $newLogId = $scrapeLog->getId();

        // Redirect to scrape logs or return the log ID in JSON
        return $this->redirectToRoute('app_scrape_logs', [
            'id' => $newLogId
        ]);

        // OR for JSON API response:
        // return $this->json(['logId' => $newLogId]);
    }
}
