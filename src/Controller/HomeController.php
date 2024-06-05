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
    //TODO refaire le menu sidebar dans le template principal uniquement pour la home (aligment des produits et des catégories menu)

    private $manager;
    public function __construct(EntityManagerInterface $manager) {
        $this->manager = $manager;
    }

    #[Route('/', name: 'app_home')]
    #[Route('/menu/sidebar/{max}', name: 'app_menu_sidebar')]
    public function homeDisplay(ProductRepository $productRepository,
        TestimonialRepository $testimonialRepository,
        CategoryRepository $categoryRepository,
        $max): Response {

        $highlight = $productRepository->findBy(['lightOn' => true], ['createdAt' => 'DESC'], 2);
        $homeBestSellers = $productRepository->findProductsByBestSells(3);
        $homeRateProducts = $productRepository->findBestRateProducts(3);
        $testimonials = $testimonialRepository->findBy([], ['createdAt' => 'DESC'], 6);

        // export depuis le menuController pour la sidebar menu
        $categories = $categoryRepository->findBy([], null, $max);
        $bestSellers = $productRepository->findProductsByBestSells(5);
        $bestRateProducts = $productRepository->findBestRateProducts(5);

        return $this->render('home/index.html.twig',
            compact('highlight',
                'homeBestSellers',
                'homeRateProducts',
                'testimonials',
                'categories',
                'bestSellers',
                'bestRateProducts'
            ));
    }
}
