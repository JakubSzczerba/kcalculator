<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="products")
 */
class Products
{
     /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;
  /**
     * @ORM\ManyToMany(targetEntity="App\Entity\UsersEntries")
     * @var UsersEntries
     */
    private $entries;
  /**
     * @ORM\Column(type="text") 
     */
    private $product;
    /**
     * @ORM\Column(type="integer") 
     */
    private $energy;
    /**
     * @ORM\Column(type="float") 
     */
    private $protein;
    /**
     * @ORM\Column(type="float") 
     */
    private $fat;
    /**
     * @ORM\Column(type="float") 
     */
    private $carbo;

    public function getId()
    {
        return $this->id;
    }

    public function getProduct(): ?text
    {
        return $this->product;
    }

    public function setProduct($product): void
    {
        $this->product = $product;
    }

    public function getEnergy(): ?int  
    {
        return $this->energy;
    }

    public function setEnergy($energy): void
    {
        $this->energy = $energy;
    }

    public function getProtein(): ?float
    {
        return $this->protein;
    }

    public function setProtein($protein): void
    {
        $this->protein = $protein;
    }

    public function getFat(): ?float
    {
        return $this->fat;
    }

    public function setFat($fat): void
    {
        $this->fat = $fat;
    }

    public function getCarbo(): ?float
    {
        return $this->carbo;
    }

    public function setCarbo($carbo): void
    {
        $this->carbo = $carbo;
    }




























}