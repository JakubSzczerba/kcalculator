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
    private $kolacja;
    /**
     * @ORM\Column(type="string") 
     */
    private $przekaski;

    public function getId()
    {
        return $this->id;
    }

    public function getSniadanie(): ?string
    {
        return $this->sniadanie;
    }

    public function setSniadanie($sniadanie): void
    {
        $this->sniadanie = $sniadanie;
    }

    public function getObiad(): ?string
    {
        return $this->obiad;
    }

    public function setObiad($obiad): void
    {
        $this->obiad = $obiad;
    }

    public function getKolacja(): ?string
    {
        return $this->kolacja;
    }

    public function setKolacja($kolacja): void
    {
        $this->kolacja = $kolacja;
    }

    public function getPrzekaski(): ?string
    {
        return $this->przekaski;
    }

    public function setPrzekaski($przekaski): void
    {
        $this->przekaski = $przekaski;
    }




















}