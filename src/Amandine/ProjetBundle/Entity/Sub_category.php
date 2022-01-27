<?php

namespace Amandine\ProjetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sub_category
 *
 * @ORM\Table(name="sub_category")
 * @ORM\Entity(repositoryClass="Amandine\ProjetBundle\Repository\Sub_categoryRepository")
 */
class Sub_category
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
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="sub_category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, cascade={"persist"}, mappedBy="sub_category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

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
     * @return Sub_category
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
}

