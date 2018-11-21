<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stock
 *
 * @ORM\Table(name="stock")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StockRepository")
 */
class Stock
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
     * @var float
     *
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad;

    /**
     * @var float
     *
     * @ORM\Column(name="precioCosto", type="float")
     */
    private $precioCosto;

//    /**
//     * @var float
//     *
//     * @ORM\Column(name="precioVenta", type="float")
//     */
//    private $precioVenta;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaActualizacion", type="datetime")
     */
    private $fechaActulizacion;
//    /**
//     * @var float
//     *
//     * @ORM\Column(name="precioVentaDescuento", type="float")
//     */
//    private $precioVentaDescuento;
    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=255)
     */
    private $codigo;

    /**
     * @ORM\ManyToOne(targetEntity="Producto", inversedBy="stock")
     * @ORM\JoinColumn(name="producto_id", referencedColumnName="id")
     */
    private $producto;

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
     * Set cantidad
     *
     * @param float $cantidad
     *
     * @return Stock
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set precioCosto
     *
     * @param float $precioCosto
     *
     * @return Stock
     */
    public function setPrecioCosto($precioCosto)
    {
        $this->precioCosto = $precioCosto;

        return $this;
    }

    /**
     * Get precioCosto
     *
     * @return float
     */
    public function getPrecioCosto()
    {
        return $this->precioCosto;
    }

    /**
     * Set precioVenta
     *
     * @param float $precioVenta
     *
     * @return Stock
     */
    public function setPrecioVenta($precioVenta)
    {
        $this->precioVenta = $precioVenta;

        return $this;
    }

    /**
     * Get precioVenta
     *
     * @return float
     */
    public function getPrecioVenta()
    {
        return $this->precioVenta;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Stock
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set fechaActulizacion
     *
     * @param \DateTime $fechaActulizacion
     *
     * @return Stock
     */
    public function setFechaActulizacion($fechaActulizacion)
    {
        $this->fechaActulizacion = $fechaActulizacion;

        return $this;
    }

    /**
     * Get fechaActulizacion
     *
     * @return \DateTime
     */
    public function getFechaActulizacion()
    {
        return $this->fechaActulizacion;
    }

    /**
     * Set precioVentaDescuento
     *
     * @param float $precioVentaDescuento
     *
     * @return Stock
     */
    public function setPrecioVentaDescuento($precioVentaDescuento)
    {
        $this->precioVentaDescuento = $precioVentaDescuento;

        return $this;
    }

    /**
     * Get precioVentaDescuento
     *
     * @return float
     */
    public function getPrecioVentaDescuento()
    {
        return $this->precioVentaDescuento;
    }

    /**
     * Set producto
     *
     * @param \AppBundle\Entity\Producto $producto
     *
     * @return Stock
     */
    public function setProducto(\AppBundle\Entity\Producto $producto = null)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get producto
     *
     * @return \AppBundle\Entity\Producto
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     *
     * @return Stock
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }
    /**
     * @ORM\ManyToOne(targetEntity="Proveedor", inversedBy="stock")
     * @ORM\JoinColumn(name="proveedor_id", referencedColumnName="id")
     */
    private $proveedor;
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
     * Set proveedor
     *
     * @param \AppBundle\Entity\Proveedor $proveedor
     *
     * @return Stock
     */
    public function setProveedor(\AppBundle\Entity\Proveedor $proveedor = null)
    {
        $this->proveedor = $proveedor;

        return $this;
    }

    /**
     * Get proveedor
     *
     * @return \AppBundle\Entity\Proveedor
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }
}
