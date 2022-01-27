<?php

namespace Amandine\ProjetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="Amandine\ProjetBundle\Repository\CommandeRepository")
 */
class Commande
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
     * @var \DateTime
     *
     * @ORM\Column(name="validating_date", type="datetime")
     */
    private $validatingDate;

    /**
     * @var decimal
     *
     * @ORM\Column(name="ht_price", type="decimal", precision=10, scale=2)
     */
    private $htPrice;

    /**
     * @var decimal
     *
     * @ORM\Column(name="ttc_price", type="decimal", precision=10, scale=2)
     */
    private $ttcPrice;

    /**
     * @var decimal
     *
     * @ORM\Column(name="ttc_price_promo", type="decimal", precision=10, scale=2)
     */
    private $ttcPricePromo;

    /**
     * @var string
     *
     * @ORM\Column(name="paiement", type="string", length=255)
     */
    private $paiement;

    /**
     * @var string
     *
     * @ORM\Column(name="livraison", type="string", length=255)
     */
    private $livraison;

    /**
     * @var decimal
     *
     * @ORM\Column(name="ttTva", type="decimal", precision=10, scale=2)
     */
    private $ttTva;

     /**
     * @var array
     *
     * @ORM\Column(name="products", type="array")
     */
    private $products = array();



    /**
    * @ORM\OneToOne(targetEntity=Basket::class)
    * @ORM\JoinColumn(nullable=false)
    
    private $basket;*/

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class,cascade={"persist"}, inversedBy="commande")
     */
    private $promo;

    /**
     * @ORM\ManyToOne(targetEntity=Amandine\UserBundle\Entity\User::class,cascade={"persist"}, inversedBy="commande")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    public function __construct()
    {
        $this->validatingDate = new \Datetime();
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
     * Set validatingDate
     *
     * @param \DateTime $validatingDate
     *
     * @return Commande
     */
    public function setValidatingDate($validatingDate)
    {
        $this->validatingDate = $validatingDate;

        return $this;
    }

    /**
     * Get validatingDate
     *
     * @return \DateTime
     */
    public function getValidatingDate()
    {
        return $this->validatingDate;
    }

    /**
     * Set htPrice
     *
     * @param decimal $htPrice
     *
     * @return Commande
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
     * Set ttcPrice
     *
     * @param decimal $ttcPrice
     *
     * @return Commande
     */
    public function setTtcPrice($ttcPrice)
    {
        $this->ttcPrice = $ttcPrice;

        return $this;
    }

    /**
     * Get ttcPrice
     *
     * @return decimal
     */
    public function getTtcPrice()
    {
        return $this->ttcPrice;
    }

    /**
     * Set ttcPricePromo
     *
     * @param decimal $ttcPricePromo
     *
     * @return Commande
     */
    public function setTtcPricePromo($ttcPricePromo)
    {
        $this->ttcPricePromo = $ttcPricePromo;

        return $this;
    }

    /**
     * Get ttcPricePromo
     *
     * @return decimal
     */
    public function getTtcPricePromo()
    {
        return $this->ttcPricePromo;
    }

    /**
     * Set paiement
     *
     * @param string $paiement
     *
     * @return Commande
     */
    public function setPaiement($paiement)
    {
        $this->paiement = $paiement;

        return $this;
    }

    /**
     * Get paiement
     *
     * @return string
     */
    public function getPaiement()
    {
        return $this->paiement;
    }

    /**
     * Set livraison
     *
     * @param string $livraison
     *
     * @return Commande
     */
    public function setLivraison($livraison)
    {
        $this->livraison = $livraison;

        return $this;
    }

    /**
     * Get livraison
     *
     * @return string
     */
    public function getLivraison()
    {
        return $this->livraison;
    }

    /**
     * Set user
     *
     * @param string $user
     *
     * @return Commande
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set ttTva
     *
     * @param decimal $ttTva
     *
     * @return Commande
     */
    public function setTtTva($ttTva)
    {
        $this->ttTva = $ttTva;

        return $this;
    }

    /**
     * Get ttTva
     *
     * @return decimal
     */
    public function getTtTva()
    {
        return $this->ttTva;
    }

    /**
     * Set products
     *
     * @param array $products
     *
     * @return Commande
     */
    public function setProducts($products)
    {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Get products
     *
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * Set promo
     *
     * @param int $promo
     *
     * @return Commande
     */
    public function setPromo($promo)
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * Get promo
     *
     * @return int
     */
    public function getPromo()
    {
        return $this->promo;
    }
}

