<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
        public function findProductsByBestSells($nb): array {
            return $this->createQueryBuilder('p')
                ->where('p.stockQuantity >= :stock')
                ->setParameter('stock', 0)
                ->orderBy('p.stockQuantity', 'ASC')
                ->setMaxResults($nb)
                ->getQuery()
                ->getResult();
            ;
        }
        public function findBestRateProducts($nb): array {
            return $this->createQueryBuilder('p')
                ->orderBy('p.rate', 'DESC')
                ->setMaxResults($nb)
                ->getQuery()
                ->getResult();
            ;
        }
        public function findRelatedProducts($categoryId, $currentProductId, $limit = 4) {
            return $this->createQueryBuilder('p')
                ->where('p.category = :categoryId')
                ->andWhere('p.id != :currentProductId')
                ->setParameter('categoryId', $categoryId)
                ->setParameter('currentProductId', $currentProductId)
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        }
        public function findDiscountProducts($value, $max) {
            return $this->createQueryBuilder('p')
                ->where('p.discount > :value')
                ->setParameter('value', $value)
                ->orderBy('p.createdAt', 'DESC')
                ->setMaxResults($max)
                ->getQuery()
                ->getResult();
        }
}
