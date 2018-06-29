<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Familia
 *
 * @ORM\Table(name="familia")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FamiliaRepository")
 */
class Familia
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
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;


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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Familia
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @ORM\OneToMany(targetEntity="Producto", mappedBy="familia")
     */
    private $producto;

    public function __construct()
    {
        $this->producto = new ArrayCollection();
    }

    /**
     * Add producto
     *
     * @param \AppBundle\Entity\Producto $producto
     *
     * @return Familia
     */
    public function addProducto(\AppBundle\Entity\Producto $producto)
    {
        $this->producto[] = $producto;

        return $this;
    }

    /**
     * Remove producto
     *
     * @param \AppBundle\Entity\Producto $producto
     */
    public function removeProducto(\AppBundle\Entity\Producto $producto)
    {
        $this->producto->removeElement($producto);
    }

    /**
     * Get producto
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducto()
    {
        return $this->producto;
    }

    public function __toString(){
        return (string) $this->descripcion;
    }
}
