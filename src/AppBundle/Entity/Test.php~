<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Test
 *
 * @ORM\Table(name="test")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TestRepository")
 */
class Test
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
     * @var \stdClass
     *
     * @ORM\Column(name="dasd", type="array")
     */
    private $dasd;


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
     * Set dasd
     *
     * @param \stdClass $dasd
     *
     * @return Test
     */
    public function setDasd($dasd)
    {
        $this->dasd = $dasd;

        return $this;
    }

    /**
     * Get dasd
     *
     * @return \stdClass
     */
    public function getDasd()
    {
        return $this->dasd;
    }
}
