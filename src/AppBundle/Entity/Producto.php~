<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Producto
 *
 * @ORM\Table(name="producto")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductoRepository")
 */
class Producto
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
     * @ORM\Column(name="titulo", type="string", length=255)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=255)
     */
    private $codigo;


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
     * Set titulo
     *
     * @param string $titulo
     *
     * @return Producto
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }



    /**
     * @ORM\ManyToOne(targetEntity="Familia", inversedBy="producto")
     * @ORM\JoinColumn(name="familia_id", referencedColumnName="id")
     */
    private $familia;


    /**
     * @ORM\ManyToOne(targetEntity="TipoUnidad", inversedBy="producto")
     * @ORM\JoinColumn(name="tipoUnidad_id", referencedColumnName="id")
     */
    private $tipoUnidad;

    /**
     * @ORM\OneToMany(targetEntity="Compra", mappedBy="producto")
     */
    private $compra;
    /**
     * @ORM\OneToMany(targetEntity="Stock", mappedBy="producto")
     */
    private $stock;

    /**
     * @ORM\OneToMany(targetEntity="DetalleVenta", mappedBy="producto")
     */
    private $detalleVenta;


    public function __construct()
    {
        $this->producto = new ArrayCollection();
        $this->stock = new ArrayCollection();
        $this->detalleVenta = new ArrayCollection();

    }

    /**
     * Set familia
     *
     * @param \AppBundle\Entity\Familia $familia
     *
     * @return Producto
     */
    public function setFamilia(\AppBundle\Entity\Familia $familia = null)
    {
        $this->familia = $familia;

        return $this;
    }

    /**
     * Get familia
     *
     * @return \AppBundle\Entity\Familia
     */
    public function getFamilia()
    {
        return $this->familia;
    }





    /**
     * Set tipoUnidad
     *
     * @param \AppBundle\Entity\TipoUnidad $tipoUnidad
     *
     * @return Producto
     */
    public function setTipoUnidad(\AppBundle\Entity\TipoUnidad $tipoUnidad = null)
    {
        $this->tipoUnidad = $tipoUnidad;

        return $this;
    }

    /**
     * Get tipoUnidad
     *
     * @return \AppBundle\Entity\TipoUnidad
     */
    public function getTipoUnidad()
    {
        return $this->tipoUnidad;
    }

    /**
     * Add compra
     *
     * @param \AppBundle\Entity\Compra $compra
     *
     * @return Producto
     */
    public function addCompra(\AppBundle\Entity\Compra $compra)
    {
        $this->compra[] = $compra;

        return $this;
    }

    /**
     * Remove compra
     *
     * @param \AppBundle\Entity\Compra $compra
     */
    public function removeCompra(\AppBundle\Entity\Compra $compra)
    {
        $this->compra->removeElement($compra);
    }

    /**
     * Get compra
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompra()
    {
        return $this->compra;
    }

    /**
     * Add stock
     *
     * @param \AppBundle\Entity\Stock $stock
     *
     * @return Producto
     */
    public function addStock(\AppBundle\Entity\Stock $stock)
    {
        $this->stock[] = $stock;

        return $this;
    }

    /**
     * Remove stock
     *
     * @param \AppBundle\Entity\Stock $stock
     */
    public function removeStock(\AppBundle\Entity\Stock $stock)
    {
        $this->stock->removeElement($stock);
    }

    /**
     * Get stock
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStock()
    {
        return $this->stock;
    }
    public function __toString(){
        return (string) $this->codigo." ".$this->titulo ." ".$this->getTipoUnidad()->getTipo();
    }


    /**
     * Set codigo
     *
     * @param string $codigo
     *
     * @return Producto
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }


    /**
     * Add detalleVentum
     *
     * @param \AppBundle\Entity\DetalleVenta $detalleVentum
     *
     * @return Producto
     */
    public function addDetalleVentum(\AppBundle\Entity\DetalleVenta $detalleVentum)
    {
        $this->detalleVenta[] = $detalleVentum;

        return $this;
    }

    /**
     * Remove detalleVentum
     *
     * @param \AppBundle\Entity\DetalleVenta $detalleVentum
     */
    public function removeDetalleVentum(\AppBundle\Entity\DetalleVenta $detalleVentum)
    {
        $this->detalleVenta->removeElement($detalleVentum);
    }

    /**
     * Get detalleVenta
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDetalleVenta()
    {
        return $this->detalleVenta;
    }
}
