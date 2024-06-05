<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\TestimonialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {
    private $manager;

    public function __construct(EntityManagerInterface $manager) {
        $this->manager = $manager;
    }

    #[Route('/', name: 'app_home')]
    public function homeDisplay(ProductRepository $productRepository, TestimonialRepository $testimonialRepository): Response {

        $highlight = $productRepository->findBy(['lightOn' => true], ['createdAt' => 'DESC'], 2);
        $homeBestSellers = $productRepository->findProductsByBestSells(3);
        $homeRateProducts = $productRepository->findBestRateProducts(3);
        $testimonials = $testimonialRepository->findBy([], ['createdAt' => 'DESC'], 6);

        return $this->render('home/index.html.twig', compact('highlight', 'homeBestSellers', 'homeRateProducts', 'testimonials'));
    }
}
