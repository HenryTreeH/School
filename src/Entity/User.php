<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Firstname = null;

    #[ORM\Column(type: "boolean", options: ["default" => 0])]
    private bool $disabled = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ScrapeLog::class)]
    private Collection $scrapeLogs;

    /**
     * @var Collection<int, ScrapeLog>
     */
    #[ORM\OneToMany(targetEntity: ScrapeLog::class, mappedBy: 'user')]
    private Collection $scraperlogs;

    public function __construct()
    {
        $this->scrapeLogs = new ArrayCollection();
        $this->scraperlogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $this->updateScraperRoleBasedOnStatus();  // Ensure roles are up-to-date
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';  // Guarantee every user has ROLE_USER
        return array_unique($roles);  // Return unique roles
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // Clear any sensitive data
    }

    public function getFirstname(): ?string
    {
        return $this->Firstname;
    }

    public function setFirstname(?string $Firstname): static
    {
        $this->Firstname = $Firstname;
        return $this;
    }

    public function getScrapeLogs(): Collection
    {
        return $this->scrapeLogs;
    }

    public function addScrapeLog(ScrapeLog $scrapeLog): static
    {
        if (!$this->scrapeLogs->contains($scrapeLog)) {
            $this->scrapeLogs->add($scrapeLog);
            $scrapeLog->setUser($this);
        }
        return $this;
    }

    public function removeScrapeLog(ScrapeLog $scrapeLog): static
    {
        if ($this->scrapeLogs->removeElement($scrapeLog)) {
            if ($scrapeLog->getUser() === $this) {
                $scrapeLog->setUser(null);
            }
        }
        return $this;
    }

    public function getScraperlogs(): Collection
    {
        return $this->scraperlogs;
    }

    public function addScraperlog(ScrapeLog $scraperlog): static
    {
        if (!$this->scraperlogs->contains($scraperlog)) {
            $this->scraperlogs->add($scraperlog);
            $scraperlog->setUser($this);
        }
        return $this;
    }

    public function removeScraperlog(ScrapeLog $scraperlog): static
    {
        if ($this->scraperlogs->removeElement($scraperlog)) {
            if ($scraperlog->getUser() === $this) {
                $scraperlog->setUser(null);
            }
        }
        return $this;
    }

    // Getter and Setter for the disabled field

    public function getDisabled(): bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled): static
    {
        $this->disabled = $disabled;
        return $this;
    }

    #[ORM\Column(type: 'boolean')]
    private bool $scraperEnabled = true;  // Default to true (enabled)

    public function getScraperEnabled(): bool
    {
        return $this->scraperEnabled;
    }

    public function setScraperEnabled(bool $scraperEnabled): self
    {
        $this->scraperEnabled = $scraperEnabled;
        $this->updateScraperRoleBasedOnStatus();  // Ensure roles are updated
        return $this;
    }

    // Update the roles array based on scraperEnabled status
    public function updateScraperRoleBasedOnStatus(): void
    {
        $roles = $this->roles;

        // Add or remove ROLE_SCRAPER based on scraperEnabled
        if ($this->scraperEnabled) {
            if (!in_array('ROLE_SCRAPER', $roles, true)) {
                $roles[] = 'ROLE_SCRAPER';
            }
        } else {
            $roles = array_filter($roles, fn($role) => $role !== 'ROLE_SCRAPER');
        }

        $this->roles = array_values($roles);  // Reindex the roles array
    }
}

