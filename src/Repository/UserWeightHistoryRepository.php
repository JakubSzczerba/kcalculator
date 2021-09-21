<?php

namespace App\Repository;

use App\Entity\UserWeightHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\String\u;
use Doctrine\ORM\Query\Expr\Join;

class UserWeightHistoryRepository extends ServiceEntityRepository
{
}