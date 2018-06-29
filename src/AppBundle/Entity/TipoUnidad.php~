<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TipoUnidad
 *
 * @ORM\Table(name="tipo_unidad")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TipoUnidadRepository")
 */
class TipoUnidad
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
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;


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
     * Set tipo
     *
     * @param string $tipo
     *
     * @return TipoUnidad
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @ORM\OneToMany(targetEntity="Producto", mappedBy="tipoUnidad")
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
     * @return TipoUnidad
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
        return (string) $this->tipo;
    }
}
