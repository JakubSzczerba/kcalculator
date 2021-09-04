<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users_weight_history)
 */
class UsersWeightHistory
{
 /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   * 
   */
  private $id;

  /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserPreferention", inversedBy="userWeightHistory")
     * @ORM\JoinColumn(name="user_preferention_id", nullable=false, referencedColumnName="id")
     */
    private $userPreferention;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(type="date") 
     */
    private $datetime;

    /**
     * @ORM\Column(type="float") 
     */
    private $userWeight;


    public function __construct()
    {
        $this->data = new \DateTime();      
    }

    public function getId()
  {
      return $this->id;
  }

  public function getUserPreferention(): ?UserPreferention
    {
        return $this->userPreferention;
    }

    public function setUserPreferention(?UserPreferention $userPreferention): self
    {
        $this->userPreferention = $userPreferention;
        return $this;
    }
    
    public function getDateTime(): \DateTime
    {
        return $this->datetime;
    }

    public function setDateTime(\DateTime $datetime): void
    {
        $this->datetime = $datetime;
    }

    public function getUserWeight(): ?float
    {
        return $this->userWeight;
    }

    public function setUserWeight($userWeight): void
    {
        $this->userWeight = $userWeight;
    }















}


