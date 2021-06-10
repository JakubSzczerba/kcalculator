<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users_entries")
 */
class UsersEntries
{
    
    /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;
  /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="id")
     * @var User
     */
    private $user;
    /**
     * @var \DateTime
     * 
     * @ORM\Column(type="date") 
     */
    private $datetime;

    /**
     * @ORM\Column(type="string") 
     */
    private $meal_type;

    /**
     * @ORM\Column(type="float") 
     */
    private $grammage;

    public function __construct()
    {
        $this->data = new \DateTime();
       
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDateTime(): \DateTime
    {
        return $this->datetime;
    }

    public function setDateTime(\DateTime $datetime): void
    {
        $this->datetime = $datetime;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(Post $user): void
    {
        $this->user = $User;
    }

    public function getMealType(): ?string
    {
        return $this->meal_type;
    }

    public function setMealType($meal_type): void
    {
        $this->meal_type = $meal_type;
    }

    public function getGrammage(): ?float
    {
        return $this->grammage;
    }

    public function setGrammage($grammage): void
    {
        $this->grammage = $grammage;
    }

}