<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface  {
  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;
  /**
   * @ORM\Column(type="string", length=128, unique=true)
   */
  private $username;
  /**
   * @ORM\Column(type="string", length=128, unique=true) 
   */
  private $email;
  /**
   * @ORM\Column(type="string", length=4096, unique=true)
   */
  private $password;
  /**
   * User constructor.
   * @param string $username
   * @param string $email
   */
  
  public function getId()
  {
      return $this->id;
  }

  public function getRoles() {
    return ['ROLE_USER'];
  }
  public function getPassword() : string {
    return $this->password;
  }
  public function getSalt() {
    // TODO: Implement getSalt() method.
  }
  public function getUsername() {
    return $this->username;
  }
  public function getEmail() {
    return $this->email;
  }
  public function eraseCredentials() {
    // TODO: Implement eraseCredentials() method.
  }
  /**
   * @param string $password
   */
  public function setPassword(string $password) {
    $this->password = $password;
  }
  public function setUsername(string $username): void
    {
        $this->username = $username;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}