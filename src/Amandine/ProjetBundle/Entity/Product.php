<?php

namespace Amandine\ProjetBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Amandine\ProjetBundle\Entity\Sub_category;
use Amandine\ProjetBundle\Entity\Comment;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="Amandine\ProjetBundle\Repository\ProductRepository")
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="maintenance", type="text")
     */
    private $maintenance;

    /**
     * @var string
     *
     * @ORM\Column(name="material", type="string", length=255)
     */
    private $material;



    /**
     * @var decimal
     *
     * @ORM\Column(name="ht_price", type="decimal", precision=10, scale=2)
     */
    private $htPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255)
     */
    private $color;

    /**
     * @var int
     *
     * @ORM\Column(name="stock", type="integer")
     */
    private $stock;

    /**
     * @ORM\ManyToOne(targetEntity=Sub_category::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $subCategory;

    /**
     * @ORM\OneToMany(targetEntity=Liaison_basket::class, cascade={"persist", "remove"}, mappedBy="product")
     */
    private $liaison_basket;


    /**
     *@var string
     *
     *@ORM\Column(name="photo", type="string")
     * @Assert\File(mimeTypes={ "image/jpeg" })
     */
    private $photo;

    /**
     *@var string
     *
     *@ORM\Column(name="photoBis", type="string")
     * @Assert\File(mimeTypes={ "image/jpeg" })
     */
    private $photoBis;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="adding_date", type="datetime")
     */
    private $addingDate;

    /**
    * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="product")
    */
    private $comment;

    public function __construct()
    {
        $this->addingDate = new \Datetime();
         $this->comment = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set maintenance
     *
     * @param string $maintenance
     *
     * @return Product
     */
    public function setMaintenance($maintenance)
    {
        $this->maintenance = $maintenance;

        return $this;
    }

    /**
     * Get maintenance
     *
     * @return string
     */
    public function getMaintenance()
    {
        return $this->maintenance;
    }

    /**
     * Set material
     *
     * @param string $material
     *
     * @return Product
     */
    public function setMaterial($material)
    {
        $this->material = $material;

        return $this;
    }

    /**
     * Get material
     *
     * @return string
     */
    public function getMaterial()
    {
        return $this->material;
    }

    

    /**
     * Set htPrice
     *
     * @param decimal $htPrice

     *
     * @return Product
     */
    public function setHtPrice($htPrice)
    {
        $this->htPrice = $htPrice;

        return $this;
    }

    /**
     * Get htPrice
     *
     * @return decimal
     */
    public function getHtPrice()
    {
        return $this->htPrice;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return Product
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     *
     * @return Product
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Product
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set photoBis
     *
     * @param string $photoBis
     *
     * @return Product
     */
    public function setPhotoBis($photoBis)
    {
        $this->photoBis = $photoBis;

        return $this;
    }

    /**
     * Get photoBis
     *
     * @return string
     */
    public function getPhotoBis()
    {
        return $this->photoBis;
    }

    /**
     * Set sub_category
     *
     * @param string $subCategory
     *
     * @return Product
     */
    public function setSubCategory($subCategory)
    {
        $this->subCategory = $subCategory;

        return $this;
    }

    /**
     * Get subCategory
     *
     * @return string
     */
    public function getSubCategory()
    {
        return $this->subCategory;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Product
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }


    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Set addingDate
     *
     * @param \DateTime $addingDate
     *
     * @return Product
     */
    public function setAddingDate($addingDate)
    {
        $this->addingDate = $addingDate;

        return $this;
    }

    /**
     * Get addingDate
     *
     * @return \DateTime
     */
    public function getAddingDate()
    {
        return $this->addingDate;
    }
}

