<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_preferention")
 */
class UserPreferention
{
    
    /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   * 
   */
  private $id;
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User")            // $sex; $weight $height $age $activity intentions caloric_requirement
    
     * @var User
     */
    private $user;

    /**
     * @ORM\Column(type="string") 
     */
    private $gender;

    /**
     * @ORM\Column(type="float") 
     */
    private $weight;

    /**
     * @ORM\Column(type="float") 
     */
    private $height;

    /**
     * @ORM\Column(type="integer") 
     */
    private $age;

    /**
     * @ORM\Column(type="string") 
     */
    private $activity;
    
    /**
     * @ORM\Column(type="integer") 
     */
    public $caloric_requirement;

    /**
     * @ORM\Column(type="string") 
     */
    private $intentions;

    /**
     * @ORM\Column(type="integer") 
     */
    public $kcal_day;


    public function getId()
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender($gender): void
    {
        $this->gender = $gender;
    }

    public function getIntentions(): ?string
    {
        return $this->intentions;
    }

    public function setIntentions($intentions): void
    {
        $this->intentions = $intentions;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight($weight): void
    {
        $this->weight = $weight;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight($height): void
    {
        $this->height = $height;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge($age): void
    {
        $this->age = $age;
    }

    public function getActivity(): ?string
    {
        return $this->activity;
    }

    public function setActivity($activity): void
    {
        $this->activity = $activity;
    }

    public function getKcal(): ?int
    {
        return $this->caloric_requirement;
    }

    public function setKcal($caloric_requirement): void
    {
        $this->caloric_requirement = $caloric_requirement;
    }

    public function getKcalDay(): ?int
    {
        return $this->kcal_day;
    }

    public function setKcalDay($kcal_day): void
    {
        $this->kcal_day = $kcal_day;
    }






































}