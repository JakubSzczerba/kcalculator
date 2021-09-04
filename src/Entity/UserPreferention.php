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
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="preferentions") 
     * @ORM\JoinColumn(name="users_id", nullable=false, referencedColumnName="id")
     */
    private $users;

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

    /**
     * @ORM\Column(type="integer") 
     */
    public $proteinPerDay;

     /**
     * @ORM\Column(type="integer") 
     */
    public $fatPerDay;

     /**
     * @ORM\Column(type="integer") 
     */
    public $carboPerDay;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UsersWeightHistory", mappedBy="userPreferention")            
     */
    private $userWeightHistory;



    public function getId()
    {
        return $this->id;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;
        return $this;
        
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
   
    public function getProteinPerDay(): ?int
    {
        return $this->proteinPerDay;
    }

    public function setProteinPerDay($proteinPerDay): void
    {
        $this->proteinPerDay = $proteinPerDay;
    }

    public function getFatPerDay(): ?int
    {
        return $this->fatPerDay;
    }

    public function setFatPerDay($fatPerDay): void
    {
        $this->fatPerDay = $fatPerDay;
    }

    public function getCarboPerDay(): ?int
    {
        return $this->carboPerDay;
    }

    public function setCarboPerDay($carboPerDay): void
    {
        $this->carboPerDay = $carboPerDay;
    }






































}