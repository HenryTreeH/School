<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class AdminController extends AbstractController
{
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/admin', name: 'app_admin')]
    public function index(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory): Response
    {
        $user = new User();
        $registrationForm = $formFactory->create(RegistrationFormType::class, $user, [
            'is_admin' => true,
        ]);

        // Fetch all users
        $users = $entityManager->getRepository(User::class)->findAll();

        // Create delete forms for each user
        $deleteForms = [];
        foreach ($users as $existingUser) {
            $deleteForms[$existingUser->getId()] = $this->createFormBuilder()
                ->setAction($this->generateUrl('admin_delete_user', ['id' => $existingUser->getId()]))
                ->setMethod('POST')
                ->add('submit', SubmitType::class, [
                    'label' => 'Delete',
                    'attr' => ['class' => 'btn btn-danger btn-sm']
                ])
                ->getForm()
                ->createView(); // Make sure you call ->createView()
        }

        return $this->render('admin/index.html.twig', [
            'registrationForm' => $registrationForm->createView(),
            'users' => $users,
            'deleteForms' => $deleteForms, // ✅ this must be included
        ]);
    }

    #[Route('/admin/create-user', name: 'admin_create_user')]
    public function createUser(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user, [
            'is_admin' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User created successfully.');
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/admin/edit-user/{id}', name: 'admin_edit_user')]
    public function editUser(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $form = $this->createForm(RegistrationFormType::class, $user, [
            'is_admin' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('plainPassword')->getData()) {
                $plainPassword = $form->get('plainPassword')->getData();
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_admin');
        }

        // Inside editUser method
        return $this->render('admin/edit_user.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);

    }

    #[Route('/admin/delete-user/{id}', name: 'admin_delete_user')]
    public function deleteUser(
        User $user,
        EntityManagerInterface $entityManager
    ): Response {
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/toggle-scraper/{id}', name: 'admin_toggle_scraper', methods: ['POST'])]
    public function toggleScraper(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $scraperEnabled = $request->request->get('scraperEnabled') === '1';

        $user->setScraperEnabled($scraperEnabled);

        $roles = $user->getRoles();

        if ($scraperEnabled) {
            if (!in_array('ROLE_SCRAPER', $roles, true)) {
                $roles[] = 'ROLE_SCRAPER';
            }
        } else {
            $roles = array_filter($roles, fn($role) => $role !== 'ROLE_SCRAPER');
        }

        $user->setRoles($roles);

        $entityManager->flush();

        $this->addFlash('success', 'User scraper status updated successfully.');

        return $this->redirectToRoute('app_admin');
    }
}

