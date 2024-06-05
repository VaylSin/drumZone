<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MenuController extends AbstractController
{
    #[Route('/menu/home/side', name: 'app_menu')]
    public function index(): Response {
        return $this->render('menu/index.html.twig', [
            'controller_name' => 'MenuController',
        ]);
    }
    #[Route('/menu/main', name: 'app_menu-main')]
    public function categoryMenu( CategoryRepository $categoryRepository): Response {
        $categories = $categoryRepository->findAll();
        return $this->render('menu/index.html.twig', compact('categories'));
    }
}
