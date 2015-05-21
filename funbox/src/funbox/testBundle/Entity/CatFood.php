<?php

namespace funbox\testBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CatFood
 *
 * @ORM\Table(name="catfood")
 * @ORM\Entity
 */
class CatFood
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="mode", type="string", length=255)
     */
    private $mode;

    /**
     * @var string
     *
     * @ORM\Column(name="topping", type="string", length=255)
     */
    private $topping;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="footer", type="text")
     */
    private $footer;


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
     * Set mode
     *
     * @param string $mode
     * @return CatFood
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Get mode
     *
     * @return string 
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set topping
     *
     * @param string $topping
     * @return CatFood
     */
    public function setTopping($topping)
    {
        $this->topping = $topping;

        return $this;
    }

    /**
     * Get topping
     *
     * @return string 
     */
    public function getTopping()
    {
        return $this->topping;
    }

    /**
     * Set description
     *
     * @param text $description
     * @return CatFood
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return CatFood
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set footer
     *
     * @param text $footer
     * @return CatFood
     */
    public function setFooter($footer)
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * Get footer
     *
     * @return text 
     */
    public function getFooter()
    {
        return $this->footer;
    }
}
