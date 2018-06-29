<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Telefono_Proveedor
 *
 * @ORM\Table(name="telefono__proveedor")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Telefono_ProveedorRepository")
 */
class Telefono_Proveedor
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
     * @ORM\Column(name="Telefono", type="string", length=255)
     */
    private $telefono;


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
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Telefono_Proveedor
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }
    /**
     * @ORM\ManyToOne(targetEntity="Proveedor", inversedBy="telefono_proveedor")
     * @ORM\JoinColumn(name="proveedor_id", referencedColumnName="id")
     */
    private $proveedor;


    /**
     * Set proveedor
     *
     * @param \AppBundle\Entity\Proveedor $proveedor
     *
     * @return Telefono_Proveedor
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
