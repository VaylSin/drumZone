<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\User; // Assurez-vous d'avoir ce use statement
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController {
    private $manager;

    public function __construct(EntityManagerInterface $manager) {
        $this->manager = $manager;
    }

    #[Route('/user', name: 'app_user')]
    public function index(): Response {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/{id}', name: 'app_user_show')]
    public function getUserInfo(int $id): Response {
        
        // Example code to retrieve user information
        $user = $this->manager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->render('user/info.html.twig', [
            'user' => $user,
        ]);
    }
        #[Route('/user/edit/{id}', name: 'app_user_edit')]
    public function editUser(Request $request, int $id): Response {
        $user = $this->manager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $form = $this->createFormBuilder($user)
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'E-mail'
            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nom'
            ])
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Prénom'
            ])
            ->add('Billing_adress', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Adresse'
            ])
            ->add('PhoneNumber', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Téléphone'
            ])

            // ->add('Modifier', SubmitType::class, ['label' => 'Modifier l\'utilisateur'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();

            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}

