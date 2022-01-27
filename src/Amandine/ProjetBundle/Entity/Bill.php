<?php

namespace Amandine\ProjetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bill
 *
 * @ORM\Table(name="bill")
 * @ORM\Entity(repositoryClass="Amandine\ProjetBundle\Repository\BillRepository")
 */
class Bill
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
     * @ORM\Column(name="billing_date", type="date")
     */
    private $billingDate;

    /**
    * @ORM\OneToOne(targetEntity=Commande::class)
    * @ORM\JoinColumn(nullable=false)
    */
    private $commande;

    /**
    * @ORM\ManyToOne(targetEntity=Amandine\UserBundle\Entity\User::class, inversedBy="bill")
    * @ORM\JoinColumn(nullable=false)
    */
    private $user;


    public function __construct()
    {
        $this->billingDate = new \Datetime();
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
     * Set billingDate
     *
     * @param \DateTime $billingDate
     *
     * @return Bill
     */
    public function setBillingDate($billingDate)
    {
        $this->billingDate = $billingDate;

        return $this;
    }

    /**
     * Get billingDate
     *
     * @return \DateTime
     */
    public function getBillingDate()
    {
        return $this->billingDate;
    }
}

