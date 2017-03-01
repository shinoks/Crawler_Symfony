<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 */
class ItemsPkt 
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
    protected $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $email;


    /**
     * @ORM\ManyToOne(targetEntity="CategoriesPkt", inversedBy="items")
     * @ORM\JoinColumn(name="categories_id", referencedColumnName="id")
     */
    protected $categories;



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
     * Set telephone
     *
     * @param string $telephone
     *
     * @return ItemsPkt
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ItemsPkt
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
     * Set email
     *
     * @param string $email
     *
     * @return ItemsPkt
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set categories
     *
     * @param \AppBundle\Entity\CategoriesPkt $categories
     *
     * @return ItemsPkt
     */
    public function setCategories(\AppBundle\Entity\CategoriesPkt $categories = null)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get categories
     *
     * @return \AppBundle\Entity\CategoriesPkt
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
