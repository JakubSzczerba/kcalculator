<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="entry")
     * @ORM\JoinColumn(name="user_id", nullable=false, referencedColumnName="id")
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

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\UserProducts", inversedBy="entries")"
     * @JoinTable(name="entry_product")
     */
    private $food;


    public function __construct()
    {
        $this->data = new \DateTime();
        $this->food = new \Doctrine\Common\Collections\ArrayCollection();
       
       
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

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
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