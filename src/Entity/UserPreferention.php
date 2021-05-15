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



}
