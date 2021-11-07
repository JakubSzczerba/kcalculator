<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture 
{
  /**
   * @var UserPasswordEncoderInterface
   */
  private $encoder;
  /**
   * @var EntityManager
   */
  private $entityManager;
  /**
   * UserFixtures constructor.
   * @param UserPasswordEncoderInterface $encoder Password encoder instance
   */
  public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager) {
    $this->encoder = $encoder;
    $this->entityManager = $entityManager;
  }
  
  public function load(ObjectManager $manager): void
    {
      $this->loadUsers($manager);
    }

    private function loadUsers(ObjectManager $manager): void
    {
        foreach ($this->getUserData() as [$fullname, $username, $email, $password, $roles]) {
            $user = new User();
            $user->setFullName($fullname);
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setPassword($this->encoder->encodePassword($user, $password));
            $user->setRoles($roles);
          

            $manager->persist($user);
            
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$fullname, $username, $email, $password, $roles];
            ['Kuba Szczerba', 'user1', 'user1@test.com', 'user', ['ROLE_USER']],
            ['Amanda Lee', 'user2', 'user2@test.com', 'user', ['ROLE_USER']],
            ['Damian Kraksa', 'user3', 'user3@test.com', 'user', ['ROLE_USER']],
            ['Bob Bobby', 'admin', 'admin@test.com', 'admin', ['ROLE_ADMIN']],
        ];
    }

        

    









}
