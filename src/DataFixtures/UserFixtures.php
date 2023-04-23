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
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture 
{
  private UserPasswordEncoderInterface $encoder;

  private EntityManagerInterface $em;

  public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
  {
    $this->encoder = $encoder;
    $this->em = $em;
  }
  
  public function load(ObjectManager $manager): void
    {
      $this->loadUsers();
    }

    private function loadUsers(): void
    {
        foreach ($this->getUserData() as [$fullName, $username, $email, $password, $roles]) {
            $user = new User();
            $user->setFullName($fullName);
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setPassword($this->encoder->encodePassword($user, $password));
            $user->setRoles($roles);
            $this->em->persist($user);
        }
        $this->em->flush();
    }

    private function getUserData(): array
    {
        return [
            ['Kuba Szczerba', 'user1', 'user1@test.com', 'user', ['ROLE_USER']],
            ['Amanda Lee', 'user2', 'user2@test.com', 'user', ['ROLE_USER']],
            ['Damian Kraksa', 'user3', 'user3@test.com', 'user', ['ROLE_USER']],
            ['Bob Bobby', 'admin', 'admin@test.com', 'admin', ['ROLE_ADMIN']],
        ];
    }
}
