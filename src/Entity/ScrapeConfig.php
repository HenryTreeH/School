<?php

// src/Entity/ScrapeConfig.php

namespace App\Entity;

use App\Repository\ScrapeConfigRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ScrapeConfigRepository::class)]
class ScrapeConfig
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $domain = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $overview_xpath = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $detail_xpath = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $title_xpath = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $price_xpath = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description_xpath = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $surface_xpath = null;

    #[ORM\Column(length: 255)]
    private ?string $bedrooms_xpath = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $photo_xpath = null;

    // Define the inverse relationship
    #[ORM\OneToMany(mappedBy: 'scrapeConfig', targetEntity: ScrapeLog::class)]
    private Collection $scrapeLogs;

    /**
     * @var Collection<int, ScrapeLog>
     */
    #[ORM\OneToMany(targetEntity: ScrapeLog::class, mappedBy: 'config')]
    private Collection $ScrapeLogs;

    public function __construct()
    {
        $this->scrapeLogs = new ArrayCollection();
        $this->ScrapeLogs = new ArrayCollection();
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): static
    {
        $this->domain = $domain;
        return $this;
    }

    public function getOverviewXpath(): ?string
    {
        return $this->overview_xpath;
    }

    public function setOverviewXpath(string $overview_xpath): static
    {
        $this->overview_xpath = $overview_xpath;
        return $this;
    }

    public function getDetailXpath(): ?string
    {
        return $this->detail_xpath;
    }

    public function setDetailXpath(string $detail_xpath): static
    {
        $this->detail_xpath = $detail_xpath;
        return $this;
    }

    public function getTitleXpath(): ?string
    {
        return $this->title_xpath;
    }

    public function setTitleXpath(string $title_xpath): static
    {
        $this->title_xpath = $title_xpath;
        return $this;
    }

    public function getPriceXpath(): ?string
    {
        return $this->price_xpath;
    }

    public function setPriceXpath(string $price_xpath): static
    {
        $this->price_xpath = $price_xpath;
        return $this;
    }

    public function getDescriptionXpath(): ?string
    {
        return $this->description_xpath;
    }

    public function setDescriptionXpath(string $description_xpath): static
    {
        $this->description_xpath = $description_xpath;
        return $this;
    }

    public function getSurfaceXpath(): ?string
    {
        return $this->surface_xpath;
    }

    public function setSurfaceXpath(string $surface_xpath): static
    {
        $this->surface_xpath = $surface_xpath;
        return $this;
    }

    public function getBedroomsXpath(): ?string
    {
        return $this->bedrooms_xpath;
    }

    public function setBedroomsXpath(string $bedrooms_xpath): static
    {
        $this->bedrooms_xpath = $bedrooms_xpath;
        return $this;
    }

    public function getPhotoXpath(): ?string
    {
        return $this->photo_xpath;
    }

    public function setPhotoXpath(string $photo_xpath): static
    {
        $this->photo_xpath = $photo_xpath;
        return $this;
    }

    // Getter for scrapeLogs
    public function getScrapeLogs(): Collection
    {
        return $this->scrapeLogs;
    }

    public function addScrapeLog(ScrapeLog $scrapeLog): static
    {
        if (!$this->ScrapeLogs->contains($scrapeLog)) {
            $this->ScrapeLogs->add($scrapeLog);
            $scrapeLog->setConfig($this);
        }

        return $this;
    }

    public function removeScrapeLog(ScrapeLog $scrapeLog): static
    {
        if ($this->ScrapeLogs->removeElement($scrapeLog)) {
            // set the owning side to null (unless already changed)
            if ($scrapeLog->getConfig() === $this) {
                $scrapeLog->setConfig(null);
            }
        }

        return $this;
    }
}

