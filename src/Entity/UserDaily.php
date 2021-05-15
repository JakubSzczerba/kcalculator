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
     * @ORM\ManyToMany(targetEntity="App\Entity\UserPreferention")
     * @var UserPreferention
     */
    private $user_pref;
    /**
     * @ORM\Column(type="datetime") 
     */
    private $data;
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