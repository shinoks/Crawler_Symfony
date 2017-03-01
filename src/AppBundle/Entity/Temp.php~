<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Temp
{
    /**
     * @ORM\Id;
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $url;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $page;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $letter;



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
     * Set url
     *
     * @param integer $url
     *
     * @return Temp
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return integer
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set page
     *
     * @param integer $page
     *
     * @return Temp
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
     * Set letter
     *
     * @param string $letter
     *
     * @return Temp
     */
    public function setLetter($letter)
    {
        $this->letter = $letter;

        return $this;
    }

    /**
     * Get letter
     *
     * @return string
     */
    public function getLetter()
    {
        return $this->letter;
    }
}
