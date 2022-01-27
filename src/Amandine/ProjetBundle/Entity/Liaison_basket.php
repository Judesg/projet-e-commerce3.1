<?php

namespace Amandine\ProjetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Liaison_basket
 *
 * @ORM\Entity(repositoryClass="Amandine\ProjetBundle\Repository\Liaison_basketRepository")
 * @ORM\Table(name="liaison_basket")
 */
class Liaison_basket
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
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="adding_date", type="datetime")
     */
    private $addingDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delete_date", type="datetime", nullable=true)
     */
    private $deleteDate;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="liaison_basket")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Basket::class, inversedBy="liaison_basket")
     * @ORM\JoinColumn(nullable=false)
     */
    private $basket;





    public function __construct()
    {
        $this->addingDate = new \Datetime();
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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Liaison_basket
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set addingDate
     *
     * @param \DateTime $addingDate
     *
     * @return Liaison_basket
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

    /**
     * Set deleteDate
     *
     * @param \DateTime $deleteDate
     *
     * @return Liaison_basket
     */
    public function setDeleteDate($deleteDate)
    {
        $this->deleteDate = $deleteDate;

        return $this;
    }

    /**
     * Get deleteDate
     *
     * @return \DateTime
     */
    public function getDeleteDate()
    {
        return $this->deleteDate;
    }

    /**
     * Set product
     *
     * @param string $product
     *
     * @return Liaison_basket
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return string
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set basket
     *
     * @param string $basket
     *
     * @return Liaison_basket
     */
    public function setBasket($basket)
    {
        $this->basket = $basket;

        return $this;
    }

    /**
     * Get basket
     *
     * @return string
     */
    public function getBasket()
    {
        return $this->basket;
    }
}

