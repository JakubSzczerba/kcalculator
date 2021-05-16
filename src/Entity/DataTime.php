<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="datatime")
 */
class DataTime
{
    
    /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;
  /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserPreferention")
     * @var UserPreferention
     */
    private $preferention;
    /**
     * @ORM\Column(type="date") 
     */
    private $data;



}