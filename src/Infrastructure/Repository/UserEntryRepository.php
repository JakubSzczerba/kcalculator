<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kcalculator\Domain\Entry\Entity\UserEntry;

class UserEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserEntry::class);
    }

    public function displayEntry(\DateTime $datetime, int $id)
    {        
        $qb = $this->createQueryBuilder('e');

        $qb->select('e')
            ->leftJoin('e.food', 'p')
            ->addSelect('p')
            ->where('e.user = :user') 
            ->andWhere('e.datetime = :datetime')     
            ->setParameter('user', $id)
            ->setParameter('datetime', $datetime->format('Y-m-d'));
            
        return $qb->getQuery()->getArrayResult();
    }

    public function SummEntriedKcal(\DateTime $datetime, int $id)
    {
        $qb = $this->createQueryBuilder('e');

        $qb->select('SUM(e.energyXgram) as totalKcal')
            ->where('e.user = :user') 
            ->andWhere('e.datetime = :datetime')   
            ->setParameter('user', $id)
            ->setParameter('datetime', $datetime->format('Y-m-d'));
      
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function ShowSnack(\DateTime $datetime, int $id, string $meal1)
    {
        $qb = $this->createQueryBuilder('e');

        $qb->select('e')    //summSnack
            ->leftJoin('e.food', 'p')
            ->addSelect('p')
            ->where('e.user = :user') 
            ->andWhere('e.datetime = :datetime')  
            ->andWhere('e.meal_type = :meal_type')
            ->setParameter('user', $id)   
            ->setParameter('meal_type', $meal1)
            ->setParameter('datetime', $datetime->format('Y-m-d'));
  
        return $qb->getQuery()->getArrayResult();
    }

    public function ShowBreakfast(\DateTime $datetime, int $id, string $meal2)
    {       
        $qb = $this->createQueryBuilder('e');

        $qb->select('e')    //summSnack
            ->leftJoin('e.food', 'p')
            ->addSelect('p')
            ->where('e.user = :user') 
            ->andWhere('e.datetime = :datetime')  
            ->andWhere('e.meal_type = :meal_type')
            ->setParameter('user', $id)   
            ->setParameter('meal_type', $meal2)
            ->setParameter('datetime', $datetime->format('Y-m-d'));
                      
        return $qb->getQuery()->getArrayResult(); 
    }

    public function ShowLunch(\DateTime $datetime, int $id, string $meal3)
    {        
        $qb = $this->createQueryBuilder('e');

        $qb->select('e')  
            ->leftJoin('e.food', 'p')
            ->addSelect('p')
            ->where('e.user = :user') 
            ->andWhere('e.datetime = :datetime')  
            ->andWhere('e.meal_type = :meal_type')
            ->setParameter('user', $id)   
            ->setParameter('meal_type', $meal3)
            ->setParameter('datetime', $datetime->format('Y-m-d'));

        return $qb->getQuery()->getArrayResult(); 
    } 

    public function ShowDinner(\DateTime $datetime, int $id, string $meal4)
    {       
        $qb = $this->createQueryBuilder('e');

        $qb->select('e')  
            ->leftJoin('e.food', 'p')
            ->addSelect('p')
            ->where('e.user = :user') 
            ->andWhere('e.datetime = :datetime')  
            ->andWhere('e.meal_type = :meal_type')
            ->setParameter('user', $id)   
            ->setParameter('meal_type', $meal4)
            ->setParameter('datetime', $datetime->format('Y-m-d'));

        return $qb->getQuery()->getArrayResult(); 
    }

    public function ShowTea(\DateTime $datetime, int $id, string $meal5)
    {        
        $qb = $this->createQueryBuilder('e');

        $qb->select('e')  
            ->leftJoin('e.food', 'p')
            ->addSelect('p')
            ->where('e.user = :user') 
            ->andWhere('e.datetime = :datetime')  
            ->andWhere('e.meal_type = :meal_type')
            ->setParameter('user', $id)   
            ->setParameter('meal_type', $meal5)
            ->setParameter('datetime', $datetime->format('Y-m-d'));

        return $qb->getQuery()->getArrayResult(); 
    }

    public function ShowSupper(\DateTime $datetime, int $id, string $meal6)
    {       
        $qb = $this->createQueryBuilder('e');

        $qb->select('e')  
            ->leftJoin('e.food', 'p')
            ->addSelect('p')
            ->where('e.user = :user') 
            ->andWhere('e.datetime = :datetime')  
            ->andWhere('e.meal_type = :meal_type')
            ->setParameter('user', $id)   
            ->setParameter('meal_type', $meal6)
            ->setParameter('datetime', $datetime->format('Y-m-d'));

        return $qb->getQuery()->getArrayResult(); 
    }

    public function SummSnacksKcal(\DateTime $datetime, int $id, string $meal1)
    {       
        $qb = $this->createQueryBuilder('e');

        $qb->select('SUM(e.energyXgram) as snackKcal')
            ->where('e.user = :user') 
            ->andWhere('e.datetime = :datetime')  
            ->andWhere('e.meal_type = :meal_type')
            ->setParameter('user', $id)
            ->setParameter('datetime', $datetime->format('Y-m-d'))
            ->setParameter('meal_type', $meal1);
            
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function SummBreakfast(\DateTime $datetime, int $id, string $meal2)
    {      
        $qb = $this->createQueryBuilder('e');

        $qb->select('SUM(e.energyXgram) as breakKcal')
            ->where('e.user = :user') 
            ->andWhere('e.datetime = :datetime')  
            ->andWhere('e.meal_type = :meal_type')
            ->setParameter('user', $id)
            ->setParameter('datetime', $datetime->format('Y-m-d'))
            ->setParameter('meal_type', $meal2);
            
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function SummLunch(\DateTime $datetime, int $id, string $meal3)
    {       
        $qb = $this->createQueryBuilder('e');

        $qb->select('SUM(e.energyXgram) as lunchKcal')
            ->where('e.user = :user') 
            ->andWhere('e.datetime = :datetime')  
            ->andWhere('e.meal_type = :meal_type')
            ->setParameter('user', $id)
            ->setParameter('datetime', $datetime->format('Y-m-d'))
            ->setParameter('meal_type', $meal3);
            
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function SummDinner(\DateTime $datetime, int $id, string $meal4)
    {      
        $qb = $this->createQueryBuilder('e');

        $qb->select('SUM(e.energyXgram) as dinnerKcal')
            ->where('e.user = :user') 
            ->andWhere('e.datetime = :datetime')  
            ->andWhere('e.meal_type = :meal_type')
            ->setParameter('user', $id)
            ->setParameter('datetime', $datetime->format('Y-m-d'))
            ->setParameter('meal_type', $meal4);
            
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return tea_kcal[]
     */
    public function SummTea(\DateTime $datetime, int $id, string $meal5)
    {       
        $qb = $this->createQueryBuilder('e');

        $qb->select('SUM(e.energyXgram) as teaKcal')
            ->where('e.user = :user') 
            ->andWhere('e.datetime = :datetime')  
            ->andWhere('e.meal_type = :meal_type')
            ->setParameter('user', $id)
            ->setParameter('datetime', $datetime->format('Y-m-d'))
            ->setParameter('meal_type', $meal5);
            
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return supper_kcal[]
     */
    public function SummSupper(\DateTime $datetime, int $id, string $meal6)
    {       
        $qb = $this->createQueryBuilder('e');

        $qb->select('SUM(e.energyXgram) as supperKcal')
            ->where('e.user = :user') 
            ->andWhere('e.datetime = :datetime')  
            ->andWhere('e.meal_type = :meal_type')
            ->setParameter('user', $id)
            ->setParameter('datetime', $datetime->format('Y-m-d'))
            ->setParameter('meal_type', $meal6);
            
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return editEntry[]
     */
    public function editEntry(int $id)
    {       
        $qb = $this->createQueryBuilder('e');

        $qb->select('e')
            ->leftJoin('e.food', 'p')
            ->addSelect('p')
            ->where('e.id = :id')            
            ->setParameter('id', $id);
                  
        return $qb->getQuery()->getArrayResult();
    }   

    /**
     * @return entried_Proteins[]
     */
    public function SummEntriedProteins(\DateTime $datetime, int $id)
    {      
        $qb = $this->createQueryBuilder('e');

        $qb->select('SUM(e.proteinXgram) as totalProteins')
            ->where('e.user = :user') 
            ->andWhere('e.datetime = :datetime')   
            ->setParameter('user', $id)
            ->setParameter('datetime', $datetime->format('Y-m-d'));
            
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return entried_Fats[]
     */
    public function SummEntriedFats(\DateTime $datetime, int $id)
    {       
        $qb = $this->createQueryBuilder('e');

        $qb->select('SUM(e.fatXgram) as totalFats')
            ->where('e.user = :user') 
            ->andWhere('e.datetime = :datetime')   
            ->setParameter('user', $id)
            ->setParameter('datetime', $datetime->format('Y-m-d'));
            
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return entried_Carbo[]
     */
    public function SummEntriedCarbo(\DateTime $datetime, int $id)
    {        
        $qb = $this->createQueryBuilder('e');

        $qb->select('SUM(e.carboXgram) as totalCarbo')
            ->where('e.user = :user') 
            ->andWhere('e.datetime = :datetime')   
            ->setParameter('user', $id)
            ->setParameter('datetime', $datetime->format('Y-m-d'));
            
        return $qb->getQuery()->getSingleScalarResult();
    }
} 

