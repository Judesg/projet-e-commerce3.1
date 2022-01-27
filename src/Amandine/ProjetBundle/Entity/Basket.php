<?php

namespace Amandine\ProjetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Basket
 *
 * @ORM\Table(name="basket")
 * @ORM\Entity(repositoryClass="Amandine\ProjetBundle\Repository\BasketRepository")
 */
class Basket
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
     * @var bool
     *
     * @ORM\Column(name="satut", type="boolean")
     */
    private $satut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * @ORM\OneToMany(targetEntity=Liaison_basket::class, cascade={"persist", "remove"}, mappedBy="basket")
     * @ORM\JoinColumn(nullable=false)
     */
    private $liaison_basket;

    /**
     * @ORM\ManyToOne(targetEntity=Amandine\UserBundle\Entity\User::class, inversedBy="basket")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    


    public function __construct()
    {
        $this->createDate = new \Datetime();
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
     * Set satut
     *
     * @param boolean $satut
     *
     * @return Basket
     */
    public function setSatut($satut)
    {
        $this->satut = $satut;

        return $this;
    }

    /**
     * Get satut
     *
     * @return bool
     */
    public function getSatut()
    {
        return $this->satut;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Basket
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     *
     * @return Basket
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Set liaisonBasket
     *
     * @param string $liaisonBasket
     *
     * @return Product
     */
    public function setLiaisonBasket($liaisonBasket)
    {
        $this->liaisonBasket = $liaisonBasket;

        return $this;
    }

    /**
     * Get liaisonBasket
     *
     * @return string
     */
    public function getLiaisonBasket()
    {
        return $this->liaisonBasket;
    }

}

