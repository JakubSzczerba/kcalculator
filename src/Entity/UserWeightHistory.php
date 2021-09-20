<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_weightHistory")
 */
class UserWeightHistory
{
    /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   * 
   */
  private $id;

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
        $this->date = new \DateTime();      
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

    public function getUserWeight(): ?float
    {
        return $this->userWeight;
    }

    public function setUserWeight($userWeight): void
    {
        $this->userWeight = $userWeight;
    }



}