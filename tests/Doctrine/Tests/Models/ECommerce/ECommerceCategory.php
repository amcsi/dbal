<?php

namespace Doctrine\Tests\Models\ECommerce;

/**
 * ECommerceCategory
 * Represents a tag applied on particular products.
 *
 * @author Giorgio Sironi
 * @Entity
 * @Table(name="ecommerce_categories")
 */
class ECommerceCategory
{
    /**
     * @Column(type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Column(type="string", length=50)
     */
    private $name;

    /**
     * @ManyToMany(targetEntity="ECommerceProduct", mappedBy="categories")
     */
    private $products;

    /**
     * @OneToMany(targetEntity="ECommerceCategory", mappedBy="parent", cascade={"save"})
     */
    private $children;

    /**
     * @ManyToOne(targetEntity="ECommerceCategory")
     * @JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\Collection();
        $this->children = new \Doctrine\Common\Collections\Collection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function addProduct(ECommerceProduct $product)
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addCategory($this);
        }
    }

    public function removeProduct(ECommerceProduct $product)
    {
        $removed = $this->products->removeElement($product);
        if ($removed !== null) {
            $removed->removeCategory($this);
        }
    }

    public function getProducts()
    {
        return $this->products;
    }

    private function setParent(ECommerceCategory $parent)
    {
        $this->parent = $parent;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function addChild(ECommerceCategory $child)
    {
        $this->children[] = $child;
        $child->setParent($this);
    }

    /** does not set the owning side. */
    public function brokenAddChild(ECommerceCategory $child)
    {
        $this->children[] = $child;
    }


    public function removeChild(ECommerceCategory $child)
    {
        $removed = $this->children->removeElement($child);
        if ($removed !== null) {
            $removed->removeParent();
        }
    }

    private function removeParent()
    {
        $this->parent = null;
    }
}