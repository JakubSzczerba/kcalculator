<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_daily")
 */
class UserDaily
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
     /**
     * @ORM\OneToOne(targetEntity="App\Entity\DataTime")
     * @var DataTime
     */
    private $datatime;
    /**
     * @ORM\Column(type="string") 
     */
    private $sniadanie;
    /**
     * @ORM\Column(type="string") 
     */
    private $obiad;
    /**
     * @ORM\Column(type="string") 
     */
    private $koalcja;
    /**
     * @ORM\Column(type="string") 
     */
    private $przekaski;

    public function getId()
    {
        return $this->id;
    }

    public function getSniadanie()
    {
        return $this->$sniadanie;
    }

    public function setSniadanie($sniadanie)
    {
        $this->sniadanie = $sniadanie;
    }

    public function getKolacja()
    {
        return $this->$koalcja;
    }

    public function setKolacja($koalcja)
    {
        $this->koalcja = $koalcja;
    }

    public function getPrzekaski()
    {
        return $this->$przekaski;
    }

    public function setPrzekaski($przekaski)
    {
        $this->przekaski = $przekaski;
    }




















}