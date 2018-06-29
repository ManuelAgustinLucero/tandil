<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Proveedor
 *
 * @ORM\Table(name="proveedor")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProveedorRepository")
 */
class Proveedor
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
     * @ORM\Column(name="Codigo", type="string", length=255, nullable=true)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;
    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=255)
     */
    private $ciudad;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaRegistro", type="datetime")
     */
    private $fechaRegistro;

    /**
     * @ORM\OneToMany(targetEntity="Compra", mappedBy="proveedor")
     */
    private $compra;
    /**
     * @ORM\OneToMany(targetEntity="Telefono_Proveedor", mappedBy="proveedor")
     */
    private $telefono_proveedor;

    public function __construct()
    {
        $this->producto = new ArrayCollection();
        $this->telefono_proveedor = new ArrayCollection();

    }

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
     * Set codigo
     *
     * @param string $codigo
     *
     * @return Proveedor
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Proveedor
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     *
     * @return Proveedor
     */
    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = $fechaRegistro;

        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return \DateTime
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    /**
     * Add compra
     *
     * @param \AppBundle\Entity\Compra $compra
     *
     * @return Proveedor
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
    public function __toString(){
        return (string) $this->codigo ."-". $this->nombre;
    }

    /**
     * Set ciudad
     *
     * @param string $ciudad
     *
     * @return Proveedor
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return string
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set telefonos
     *
     * @param string $telefonos
     *
     * @return Proveedor
     */
    public function setTelefonos($telefonos)
    {
        $this->telefonos = $telefonos;

        return $this;
    }



    /**
     * Add telefonoProveedor
     *
     * @param \AppBundle\Entity\Telefono_Proveedor $telefonoProveedor
     *
     * @return Proveedor
     */
    public function addTelefonoProveedor(\AppBundle\Entity\Telefono_Proveedor $telefonoProveedor)
    {
        $this->telefono_proveedor[] = $telefonoProveedor;

        return $this;
    }

    /**
     * Remove telefonoProveedor
     *
     * @param \AppBundle\Entity\Telefono_Proveedor $telefonoProveedor
     */
    public function removeTelefonoProveedor(\AppBundle\Entity\Telefono_Proveedor $telefonoProveedor)
    {
        $this->telefono_proveedor->removeElement($telefonoProveedor);
    }

    /**
     * Get telefonoProveedor
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTelefonoProveedor()
    {
        return $this->telefono_proveedor;
    }
}
