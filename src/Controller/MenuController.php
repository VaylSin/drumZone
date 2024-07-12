<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuController extends AbstractController {

    private $manager;

    public function __construct(EntityManagerInterface $manager) {
        $this->manager = $manager;
    }

    #[Route('/menu/home/side', name: 'app_menu')]
    public function index(): Response {
        return $this->render('menu/index.html.twig', [
            'controller_name' => 'MenuController',
        ]);
    }
    #[Route('/menu/main', name: 'app_menu-main')]
    public function categoryMenu( CategoryRepository $categoryRepository, ): Response {
        $categories = $categoryRepository->findAll();
        return $this->render('menu/index.html.twig', compact('categories'));
    }

}

