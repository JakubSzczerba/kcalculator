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
     * @ORM\OneToOne(targetEntity="App\Entity\UserPreferention", mappedBy="users")            
     */
    private $preferentions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UsersEntries", mappedBy="user")            
     */
    private $entry;


  /**
   * User constructor.
   * @param string $username
   * @param string $email
   */
  public function __construct(string $username, string $email) 
  {
    $this->username = $username;
    $this->email = $email;
  }
  
  public function getId()
  {
      return $this->id;
  }
  
  public function getPreferention(): ?UserPreferention
    {
        return $this->preferentions;
    }
    public function setPreferention($preferentions): void
    {
        $this->preferentions = $preferentions;
    }


  public function getRoles() {
    return ['ROLE_USER'];
  }
  public function getPassword(): string 
  {
    return $this->password;
    
  }
  public function getSalt() {
    // TODO: Implement getSalt() method.
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

    
}
