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


}