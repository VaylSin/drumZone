<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FooterController extends AbstractController
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
    }

    #[Route('/footer', name: 'app_footer')]
    public function footerDisplay(ProductRepository $productRepository): Response {
        $productsCloud = $productRepository->findAll();
        
        return $this->render('footer/index.html.twig', compact('productsCloud') );
    }
}
