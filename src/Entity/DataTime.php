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
     * @ORM\ManyToOne(targetEntity="App\Entity\UserPreferention", inversedBy="id")
     * @var UserPreferention
     */
    private $preferention;
    /**
     * @var \DateTime
     * 
     * @ORM\Column(type="date") 
     */
    private $data;

    public function __construct()
    {
        $this->data = new \DateTime();
       
    }

    public function getId()
    {
        return $this->id;
    }

    public function getData(): \DateTime
    {
        return $this->data;
    }

    public function setData(\DateTime $data): void
    {
        $this->data = $data;
    }

    public function getPreferention(): ?UserPreferention
    {
        return $this->preferention;
    }

    public function setPreferention(Post $preferention): void
    {
        $this->preferention = $preferention;
    }



}