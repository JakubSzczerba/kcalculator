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

    public function getCel()
    {
        return $this->$cel;
    }

    public function setCel($cel)
    {
        $this->cel = $cel;
    }

    public function getWaga()
    {
        return $this->$waga;
    }

    public function setWaga($waga)
    {
        $this->waga = $waga;
    }

    public function getKcal()
    {
        return $this->$kcal;
    }

    public function setKcal($kcal)
    {
        $this->kcal = $kcal;
    }






































}
