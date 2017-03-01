<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CategoriesPkt
{
    /**
     * @ORM\Id;
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $address;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $downloaded;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $page;

    /**
     * @ORM\OneToMany(targetEntity="ItemsPkt", mappedBy="categories")
     * @ORM\JoinColumn(name="items_id", referencedColumnName="id")
     */
    protected $items;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
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
     * @return CategoriesPkt
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
     * Set address
     *
     * @param string $address
     *
     * @return CategoriesPkt
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set downloaded
     *
     * @param boolean $downloaded
     *
     * @return CategoriesPkt
     */
    public function setDownloaded($downloaded)
    {
        $this->downloaded = $downloaded;

        return $this;
    }

    /**
     * Get downloaded
     *
     * @return boolean
     */
    public function getDownloaded()
    {
        return $this->downloaded;
    }

    /**
     * Set page
     *
     * @param integer $page
     *
     * @return CategoriesPkt
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return integer
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Add item
     *
     * @param \AppBundle\Entity\ItemsPkt $item
     *
     * @return CategoriesPkt
     */
    public function addItem(\AppBundle\Entity\ItemsPkt $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \AppBundle\Entity\ItemsPkt $item
     */
    public function removeItem(\AppBundle\Entity\ItemsPkt $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }
}