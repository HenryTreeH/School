<?php

namespace App\Controller;

use App\Entity\AuditLog;
use App\Entity\ScrapeConfig;
use App\Form\ScrapeConfigType;
use App\Repository\ScrapeLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Service\AuditService;

class ScrapeConfigController extends AbstractController
{
    private AuditService $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    #[Route('/scrape-config', name: 'app_scrape_config')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        ScrapeLogRepository $scrapeLogRepository
    ): Response {
        $scrapeConfig = new ScrapeConfig();
        $form = $this->createForm(ScrapeConfigType::class, $scrapeConfig);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($scrapeConfig);
            $entityManager->flush();

            // Log the audit
            $this->auditService->logAudit(
                'scrape_config',
                $scrapeConfig->getId(),
                'create',
                [],
                $this->getUser()?->getUserIdentifier() ?? 'anonymous'
            );

            $this->addFlash('success', 'Scrape configuration saved successfully!');
            return $this->redirectToRoute('app_scrape_config');
        }

        return $this->render('scrape_config/index.html.twig', [
            'form' => $form->createView(),
            'configs' => $entityManager->getRepository(ScrapeConfig::class)->findAll(),
            'stats' => $scrapeLogRepository->getScrapeStats(),
            'recentLogs' => $scrapeLogRepository->findRecentLogs()
        ]);
    }

    #[Route('/scrape-config/edit/{id}', name: 'app_scrape_config_edit')]
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        ScrapeConfig $scrapeConfig
    ): Response {
        $originalData = clone $scrapeConfig; // ✅ Clone BEFORE handling the form request

        $scrapeConfigForm = $this->createForm(ScrapeConfigType::class, $scrapeConfig);
        $scrapeConfigForm->handleRequest($request);

        if ($scrapeConfigForm->isSubmitted() && $scrapeConfigForm->isValid()) {
            $entityManager->flush();

            // Compare original vs updated fields
            $changeSet = $this->auditService->getChangeSet($originalData, $scrapeConfig, [
                'name', 'xpath' // Add more fields here if needed
            ]);

            $this->auditService->logAudit(
                'scrape_config',
                $scrapeConfig->getId(),
                'update',
                !empty($changeSet) ? $changeSet : ['message' => 'No changes detected'],
                $this->getUser()?->getUserIdentifier() ?? 'anonymous'
            );

            $this->addFlash('success', 'Scrape configuration updated successfully!');
            return $this->redirectToRoute('app_scrape_config');
        }

        return $this->render('scrape_config/edit.html.twig', [
            'form' => $scrapeConfigForm->createView(),
            'scrapeConfig' => $scrapeConfig,
        ]);
    }


    #[Route('/scrape-config/delete/{id}', name: 'app_scrape_config_delete')]
    #[IsGranted("ROLE_ADMIN")]
    public function delete(
        Request $request,
        EntityManagerInterface $entityManager,
        ScrapeConfig $scrapeConfig
    ): Response {
        // Log the audit before deleting
        $changeSet = get_object_vars($scrapeConfig); // Capture all fields of the entity
        $this->auditService->logAudit(
            'scrape_config',
            $scrapeConfig->getId(),
            'delete',
            $changeSet,
            $this->getUser()?->getUserIdentifier() ?? 'anonymous'
        );

        $entityManager->remove($scrapeConfig);
        $entityManager->flush();
        $this->addFlash('success', 'Scrape configuration deleted successfully!');
        return $this->redirectToRoute('app_scraped_pages');
    }

    #[Route('/scrape-config/audit-log', name: 'app_scrape_config_audit_log')]
    public function auditLog(EntityManagerInterface $em): Response
    {
        // Fetch all audit logs
        $logs = $em->getRepository(AuditLog::class)->findAll();

        return $this->render('audit_log/index.html.twig', [
            'logs' => $logs,
        ]);
    }
}
