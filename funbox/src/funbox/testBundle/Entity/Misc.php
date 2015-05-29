<?php

namespace funbox\testBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Misc
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Misc
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
     * @ORM\Column(name="h1", type="string")
     */
    private $h1;

    /**
     * @var text
     *
     * @ORM\Column(name="footer", type="text")
     */
    private $footer;

    /**
     * Set h1
     *
     * @param text $h1
     * @return Misc
     */
    public function setH1($h1)
    {
        $this->h1 = $h1;

        return $this;
    }

    /**
     * Get h1
     *
     * @return string 
     */
    public function getH1()
    {
        return $this->h1;
    }

    /**
     * Set text
     *
     * @param text $footer
     * @return Misc
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
