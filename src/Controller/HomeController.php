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
    private $productRepository;
    public function __construct(EntityManagerInterface $manager, ProductRepository $productRepository) {
        $this->manager = $manager;
        $this->productRepository = $productRepository;
    }

    #[Route('/', name: 'app_home')]
    #[Route('/menu/sidebar/{max}', name: 'app_menu_sidebar')]
    public function homeDisplay(TestimonialRepository $testimonialRepository,
        CategoryRepository $categoryRepository,
        $max = null): Response {

        $highlight = $this->productRepository->findBy(['lightOn' => true], ['createdAt' => 'DESC'], 2);
        $homeBestSellers = $this->productRepository->findProductsByBestSells(3);
        $homeRateProducts = $this->productRepository->findBestRateProducts(3);
        $testimonials = $testimonialRepository->findBy([], ['createdAt' => 'DESC'], 6);
        $productsCloud = $this->productRepository->findAll();

        // export depuis le menuController pour la sidebar menu
        $categories = $categoryRepository->findBy([], null, $max);
        $bestSellers = $this->productRepository->findProductsByBestSells(5);
        $bestRateProducts = $this->productRepository->findBestRateProducts(5);

        return $this->render('home/index.html.twig',
            compact('highlight',
                'homeBestSellers',
                'homeRateProducts',
                'testimonials',
                'categories',
                'bestSellers',
                'bestRateProducts',
                'productsCloud'
            ));
    }

    #[Route('/home/block/headlines/{max}', name: 'app_home_headlines')]
    public function homeHeadlineMenu(int $max, int $count ): Response {
        // $headlineProducts = $this->productRepository->findBy(['lightOn' => true], ['createdAt' => 'DESC'], $max);
        $discountProducts = $this->productRepository->findDiscountProducts(0, 4);
        return $this->render('home/headlines_block.html.twig', [
            'headlineProducts' => $discountProducts,
            'count' => $count,
            'isFromHeadlines' => true,
        ]);
    }

    #[Route('/home/block/cat/{max}', name: 'app_home_cat')]
    public function homeHighlightMenu(CategoryRepository $categoryRepository, $max ): Response {
        $categories = $categoryRepository->findBy([], null, $max);
        $highlight = $this->productRepository->findBy(['lightOn' => true], ['createdAt' => 'DESC'], 2);

        return $this->render('home/highlight_block.html.twig', compact('categories', 'highlight'));
    }

    #[Route('/home/block/sells/{max}', name: 'app_home_sells')]
    public function homeSellersMenu(int $max, int $count ): Response {
        $bestSellersLinks = $this->productRepository->findProductsByBestSells(5);
        $bestSellersCards = $this->productRepository->findProductsByBestSells(3);
        return $this->render('home/bestSells_block.html.twig', compact('bestSellersLinks', 'bestSellersCards', 'count'));
    }

    #[Route('/home/block/rates/{max}', name: 'app_home_rates')]
    public function homeRatesMenu(int $max, int $count ): Response {
        $bestRateLinks = $this->productRepository->findBestRateProducts(5);
        $bestRateCards = $this->productRepository->findBestRateProducts(3);

        return $this->render('home/bestRates_block.html.twig', compact('bestRateLinks', 'bestRateCards', 'count'));
    }

    #[Route('/home/block/product-cloud/{max}', name: 'app_home_product-cloud')]
    public function homeProductCloudMenu(int $max): Response {
        $products = $this->productRepository->findBy([], ['createdAt' => 'DESC'], $max);
        return $this->render('base/footer.html.twig', compact('products'));
    }

}
