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
        $max = null): Response {

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
    
    #[Route('/home/block/cat/{max}', name: 'app_home_cat')]
    public function homeHighlightMenu(ProductRepository $productRepository, CategoryRepository $categoryRepository, $max ): Response {
        $categories = $categoryRepository->findBy([], null, $max);
        $highlight = $productRepository->findBy(['lightOn' => true], ['createdAt' => 'DESC'], 2);

        return $this->render('home/highlight_block.html.twig', compact('categories', 'highlight'));
    }

    #[Route('/home/block/sells/{max}', name: 'app_home_sells')]
    public function homeSellersMenu(ProductRepository $productRepository, int $max, int $count ): Response {
        $bestSellersLinks = $productRepository->findProductsByBestSells(5);
        $bestSellersCards = $productRepository->findProductsByBestSells(3);
        return $this->render('home/bestSells_block.html.twig', compact('bestSellersLinks', 'bestSellersCards', 'count'));
    }

    #[Route('/home/block/rates/{max}', name: 'app_home_rates')]
    public function homeRatesMenu(ProductRepository $productRepository, int $max, int $count ): Response {
        $bestRateLinks = $productRepository->findBestRateProducts(5);
        $bestRateCards = $productRepository->findBestRateProducts(3);

        return $this->render('home/bestRates_block.html.twig', compact('bestRateLinks', 'bestRateCards', 'count'));
    }
}
