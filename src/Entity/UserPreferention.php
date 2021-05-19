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
   * @ORM\OneToMany(targetEntity="App\Entity\DataTime", mappedBy="preferention")
   */
  private $id;
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User")
     * @var User
     */
    private $user;

    /**
     * @ORM\Column(type="string") 
     */
    private $cel;
    /**
     * @ORM\Column(type="float") 
     */
    private $waga;
    /**
     * @ORM\Column(type="integer") 
     */
    private $kcal;


    public function getId()
    {
        return $this->id;
    }

    public function getCel(): ?string
    {
        return $this->cel;
    }

    public function setCel($cel): void
    {
        $this->cel = $cel;
    }

    public function getWaga(): ?string
    {
        return $this->waga;
    }

    public function setWaga($waga): void
    {
        $this->waga = $waga;
    }

    public function getKcal(): ?string
    {
        return $this->kcal;
    }

    public function setKcal($kcal): void
    {
        $this->kcal = $kcal;
    }






































}
