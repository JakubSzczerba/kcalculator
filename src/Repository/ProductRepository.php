<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\String\u;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    /**
     * @return products[]
     */

    public function findProducts(string $product): array
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('p')
            //->select('p')
            //->select('p.product', 'p.energy', 'p.protein', 'p.fat', 'p.carbo')
            ->where('p.product LIKE :product')        
            ->setParameter('product', '%'.$product.'%')
            ->orderBy('p.product', 'ASC');

            
        //dump($qb->getQuery()->getResult());
        return $qb->getQuery()->getArrayResult();

    }

    /**
     * @return product[]
     */

    public function checkedProduct(int $oneProduct)
    {
        $qb = $this->createQueryBuilder('check');

        $qb->select('check')
            ->where('check.id = :check')  
            ->setParameter('check', $oneProduct);
            
            

            
        dump($qb->getQuery()->getResult());
        return $qb->getQuery()->getArrayResult();




    }


    








       
    
        
        

    


































    

}