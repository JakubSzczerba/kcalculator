<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="`user`")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface  
{
  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
    * @var string
    *
    * @ORM\Column(type="string")
  */
  private $fullName;

  /**
   * @ORM\Column(type="string", length=128, unique=true)
   */
  private $username;

  /**
   * @ORM\Column(type="string", length=128, unique=true)
   */
  private $email;

  /**
   * @ORM\Column(type="string", length=4096)
   */
  private $password;

  /**
   * @var array
   * 
   * @ORM\Column(type="json")
   */
  private $roles = [];

  /**
    * @ORM\OneToOne(targetEntity="App\Entity\UserPreferention", mappedBy="users")            
    */
  private $preferentions;

  /**
    * @ORM\OneToMany(targetEntity="App\Entity\UsersEntries", mappedBy="user")            
    */
  private $entry;

  /**
    * @ORM\OneToMany(targetEntity="App\Entity\UserWeightHistory", mappedBy="user")            
    */
  private $userWeightHistory;

  /**
   * User constructor.
   * @param string $username
   * @param string $email
   */

  public function __construct(string $fullName, string $username, string $email) 
  {
    $this->fullName = $fullName;
    $this->username = $username; 
    $this->email = $email;      
    /* nedd to comment constructor for new fixtures load. */
  }
 
  public function getId()
  {
    return $this->id;
  }

  public function setFullName(string $fullName): void
  {
    $this->fullName = $fullName;
  }

  public function getFullName(): ?string
  {
    return $this->fullName;
  }
  
  /**
   * Returns the roles or permissions granted to the user for security.
   */
  public function getRoles(): array
  {
    $roles = $this->roles;

    if (empty($roles)) {
      $roles[] = 'ROLE_USER';
      }

    return array_unique($roles);
  }

  public function setRoles(array $roles): void
  {
    $this->roles = $roles;
  }

  public function getPassword(): string 
  {
    return $this->password;  
  }

  /**
   * Returns the salt that was originally used to encode the password.
   * 
   * {@inheritdoc}
   */
  public function getSalt(): ?string
  {
    // We're using bcrypt in security.yaml to encode the password, so
    // the salt value is built-in and and you don't have to generate one
    // See https://en.wikipedia.org/wiki/Bcrypt

    return null;
  }

  public function getUsername(): string
  {
    return $this->username;
  }

  public function getEmail(): string
  {
    return $this->email;
  }

  public function eraseCredentials() {
    // TODO: Implement eraseCredentials() method.
  }

  /**
   * @param string $password
   */
  public function setPassword(string $password): self
  {
    $this->password = $password;
    return $this;  
  }

  public function setUsername(string $username): self
    {
      $this->username = $username;
      return $this;
    }

  public function setEmail(string $email): self
  {
    $this->email = $email;
    return $this;
  }

  public function getPreferention(): ?UserPreferention
  {
    return $this->preferentions;
  }

  public function setPreferention($preferentions): void
  {
    $this->preferentions = $preferentions;
  }

  public function getEntries(): ?UsersEntries
  {
    return $this->entry;
  }

  public function setEntries($entry): void
  {
    $this->entry = $entry;
  }

  public function getUerWeightHistory(): ?UserWeightHistory
  {
    return $this->userWeightHistory;
  }

  public function setUerWeightHistory($userWeightHistory): void
    {
      $this->userWeightHistory = $userWeightHistory;
    }
}
