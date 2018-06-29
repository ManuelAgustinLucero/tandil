<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 15/06/18
 * Time: 18:47
 */

namespace AppBundle\Controller;




use AppBundle\Entity\DetalleVenta;
use AppBundle\Entity\Venta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Cliente;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Exception\ValidatorException;


use Symfony\Component\HttpFoundation\Response;
class ExportPdfFactura extends Controller
{
    /**
     * Creates a new cliente entity.
     *
     * @Route("/venta/pdfVenta/{id}", name="pdfVenta")
     * @Method({"GET", "POST"})
     */
    public function pdfVentaAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();

        $venta = $em->getRepository('AppBundle:Venta')->find($id);
        $productoVenta = $em->getRepository('AppBundle:DetalleVenta')->findBy(['venta' => $id]);

        $pdf = new \FPDF();

        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);


        $pdf->SetFont('Arial', '', 12);

        $pdf->MultiCell(190, 16, '', 1, 'C');

        $pdf->Image('assets/img/logo.jpeg', 10, 10, 30);


        $pdf->MultiCell(0, 30, '', 1, 'C');
        $pdf->SetXY(15, 30);
        $pdf->MultiCell(0, 5, 'Nombre: ' . utf8_decode($venta->getCliente()->getNombre()), 0, '');

        $pdf->SetXY(15, 40);
        $pdf->MultiCell(0, 5, 'Direccion: ' . utf8_decode($venta->getCliente()->getDireccion()), 0, '');

        $pdf->SetXY(15, 50);
        $pdf->MultiCell(0, 5, 'Telefono: ' . utf8_decode($venta->getCliente()->getTelefono()), 0, '');

        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(145, 30);
        $pdf->MultiCell(0, 5, 'Fecha emision: ' . $venta->getFecha()->format('d/m/Y'), 0, '');

        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(145, 40);
        $pdf->MultiCell(0, 5, 'Localidad: ' . utf8_decode($venta->getCliente()->getCiudad()), 0, '');
        $pdf->Ln(10);


        $pdf->SetFont('Arial', 'B', 9);

        $pdf->SetXY(10, 56);
        $pdf->MultiCell(0, 10, utf8_decode('ITEMS'), 0, '');
        $pdf->SetXY(10, 63);
        $pdf->MultiCell(20, 5, utf8_decode('Código'), 1, 'C');
        $pdf->SetXY(30, 63);
        $pdf->MultiCell(110, 5, utf8_decode('Nombre'), 1, 'L');
        $pdf->SetXY(140, 63);
        $pdf->MultiCell(20, 5, utf8_decode('Precio Unit.'), 1, 'L');
        $pdf->SetXY(160, 63);
        $pdf->MultiCell(20, 5, utf8_decode('Cantidad'), 1, 'L');
        $pdf->SetXY(180, 63);
        $pdf->MultiCell(20, 5, utf8_decode('Total'), 1, 'L');


        $posY = 70;

//        Original
        $this->createBlackLineTable($pdf, $posY);

//        $datos = ["manzana","pera"];

        $cant = 0;
        foreach ($productoVenta as $key => $producto) {
            if ($cant > 36) {
                $this->newPage($pdf);
                $cant = 0;
                $posY = 65;
            }
            $this->createLineTable($key, $producto, $posY, $pdf);
            $posY += 5;
            $cant++;
        }
//        $this->createLineDeuda("", $venta->getTotalPagado(), $posY,$pdf);
        $this->createLineTotal($venta->getTotal(), $posY, $pdf);

//        $pdf->SetFont('Arial', '', 12);
//        $pdf->SetXY(10, $posY + 10);
//        $pdf->MultiCell(20, 5, utf8_decode('Deuda:'), 2, 'L');
//        $pdf->SetXY(30, $posY + 10);
//        $pdf->MultiCell(20, 5, utf8_decode('$'), 1, 'L');
//        $posY += 5;
        $cant++;

//
//        $this->createRetirementTable($posY,$pdf);

        //NUEVA PARTE
//        $pdf->Line(0, 130, 300, 130); // 20mm from each edge
        if (count($productoVenta) > 8) {

//            $this->newPage($pdf);
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);


            $pdf->SetFont('Arial', '', 12);

            $pdf->MultiCell(190, 16, '', 1, 'C');

            $pdf->Image('assets/img/logo.jpeg', 10, 10, 30);


            $pdf->MultiCell(0, 30, '', 1, 'C');
            $pdf->SetXY(15, 30);
            $pdf->MultiCell(0, 5, 'Nombre: ' . utf8_decode($venta->getCliente()->getNombre()), 0, '');

            $pdf->SetXY(15, 40);
            $pdf->MultiCell(0, 5, 'Direccion: ' . utf8_decode($venta->getCliente()->getDireccion()), 0, '');

            $pdf->SetXY(15, 50);
            $pdf->MultiCell(0, 5, 'Telefono: ' . utf8_decode($venta->getCliente()->getTelefono()), 0, '');

            $pdf->SetFont('Arial', '', 12);
            $pdf->SetXY(145, 30);
            $pdf->MultiCell(0, 5, 'Fecha emision: ' . $venta->getFecha()->format('d/m/Y'), 0, '');

            $pdf->SetFont('Arial', '', 12);
            $pdf->SetXY(145, 40);
            $pdf->MultiCell(0, 5, 'Localidad: ' . utf8_decode($venta->getCliente()->getCiudad()), 0, '');
            $pdf->Ln(10);
            //$this->Image($_SERVER['DOCUMENT_ROOT'].'/includes/images/iso/9105078191_139737_T.jpg', 15, 100, 180, 90);

            $pdf->SetFont('Arial', 'B', 8);

            $pdf->SetXY(10, 56);
            $pdf->MultiCell(0, 10, utf8_decode('ITEMS'), 0, '');
            $pdf->SetXY(10, 63);
            $pdf->MultiCell(20, 5, utf8_decode('Código'), 1, 'C');
            $pdf->SetXY(30, 63);
            $pdf->MultiCell(110, 5, utf8_decode('Nombre'), 1, 'L');
            $pdf->SetXY(140, 63);
            $pdf->MultiCell(20, 5, utf8_decode('Precio Unit.'), 1, 'L');
            $pdf->SetXY(160, 63);
            $pdf->MultiCell(20, 5, utf8_decode('Cantidad'), 1, 'L');
            $pdf->SetXY(180, 63);
            $pdf->MultiCell(20, 5, utf8_decode('Total'), 1, 'L');


            $posY = 70;

            //Original
            $this->createBlackLineTable($pdf, $posY);

            $datos = ["manzana", "pera"];

            $cant = 0;
            foreach ($productoVenta as $key => $producto) {
                if ($cant > 36) {
                    $this->newPage($pdf);
                    $cant = 0;
                    $posY = 65;
                }
                $this->createLineTable($key, $producto, $posY, $pdf);

                $posY += 5;
                $cant++;
            }

            $this->createLineTotal($venta->getTotal(), $posY, $pdf);

//
//            $this->createRetirementTable($posY,$pdf);
        } else {
            //Copia

            $posYCopia = 150;
            $pdf->SetXY(10, 10 + $posYCopia);
            $pdf->SetFont('Arial', '', 12);


            $pdf->MultiCell(190, 16, ' ', 1, 'C');
            $pdf->Image('assets/img/logo.jpeg', 10, 160, 30);

            $pdf->MultiCell(0, 30, '', 1, 'C');
            $pdf->SetXY(15, 30 + $posYCopia);
            $pdf->MultiCell(0, 5, 'Nombre: ' . utf8_decode($venta->getCliente()->getNombre()), 0, '');

            $pdf->SetXY(15, 40 + $posYCopia);
            $pdf->MultiCell(0, 5, 'Direccion: ' . utf8_decode($venta->getCliente()->getDireccion()), 0, '');

            $pdf->SetXY(15, 50 + $posYCopia);
            $pdf->MultiCell(0, 5, 'Telefono: ' . utf8_decode($venta->getCliente()->getTelefono()), 0, '');

            $pdf->SetFont('Arial', '', 12);
            $pdf->SetXY(145, 30 + $posYCopia);
            $pdf->MultiCell(0, 5, 'Fecha emision: ' . $venta->getFecha()->format('d/m/Y'), 0, '');

            $pdf->SetFont('Arial', '', 12);
            $pdf->SetXY(145, 40 + $posYCopia);
            $pdf->MultiCell(0, 5, 'Localidad: ' . utf8_decode($venta->getCliente()->getCiudad()), 0, '');

            $pdf->Ln(10);


            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetXY(10, 56 + $posYCopia);
            $pdf->MultiCell(0, 10, utf8_decode('ITEMS'), 0, '');
            $pdf->SetXY(10, 63 + $posYCopia);
            $pdf->MultiCell(20, 5, utf8_decode('Código'), 1, 'C');
            $pdf->SetXY(30, 63 + $posYCopia);
            $pdf->MultiCell(110, 5, utf8_decode('Nombre'), 1, 'L');
            $pdf->SetXY(140, 63 + $posYCopia);
            $pdf->MultiCell(20, 5, utf8_decode('Precio Unit.'), 1, 'L');
            $pdf->SetXY(160, 63 + $posYCopia);
            $pdf->MultiCell(20, 5, utf8_decode('Cantidad'), 1, 'L');
            $pdf->SetXY(180, 63 + $posYCopia);
            $pdf->MultiCell(20, 5, utf8_decode('Total'), 1, 'L');


            $posYCopia = 70 + $posYCopia;
            $this->createBlackLineTable($pdf, $posY);

            $datos = ["manzana", "pera"];

            $cant = 0;
            foreach ($productoVenta as $key => $producto) {
                $this->createLineTable($key, $producto, $posYCopia, $pdf);
                $posYCopia += 5;
                $cant++;
            }

//            $this->createLineDeuda("", $venta->getTotalPagado(), $posYCopia,$pdf);
            $this->createLineTotal($venta->getTotal(), $posYCopia, $pdf);
            $posYCopia += 5;
            $cant++;
//
//            $pdf->SetFont('Arial', '', 12);
//            $pdf->SetXY(10, $posYCopia + 5);
//            $pdf->MultiCell(20, 5, utf8_decode('Deuda:'), 2, 'L');
//            $pdf->SetXY(30, $posYCopia + 5);
//            $pdf->MultiCell(20, 5, utf8_decode('$'), 1, 'L');


//            $this->createRetirementTable($posYCopia,$pdf);
        }
        return new Response($pdf->Output(), 200, array(
            'Content-Type' => 'application/pdf'));
    }
    function createLineTotal($total, $posY,$pdf)
    {
        $pdf->SetFont('Arial', '',14);
        $pdf->SetXY(10, $posY);
        $pdf->MultiCell(20, 5.2, "", 1, 'C');
        $pdf->SetXY(30, $posY);
        $pdf->MultiCell(110, 5.2, "", 1, 'L');
        $pdf->SetXY(140, $posY);
        $pdf->MultiCell(20, 5.2, "", 1, 'C');
        $pdf->SetXY(160, $posY);
        $pdf->MultiCell(20, 5.2, "Total:", 1, 'C');
        $pdf->SetXY(180, $posY);
        $pdf->MultiCell(20, 5.2, "$" . utf8_decode($total), 1, 'C');
    }

    function createLineTable($key, $producto, $posY,$pdf)
    {

        $pdf->SetFont('Arial', '', 10);

        $pdf->SetXY(10, $posY);
        $pdf->MultiCell(20, 5, ($producto->getProducto()->getId()), 1, 'C');
        $pdf->SetXY(30, $posY);
        $pdf->MultiCell(110, 5,  $producto->getProducto()->getTitulo(), 1, 'L');
        $pdf->SetXY(140, $posY);
        $pdf->MultiCell(20, 5, "$" . $producto->getPrecio(), 1, 'C');
        $pdf->SetXY(160, $posY);
        $pdf->MultiCell(20, 5, $producto->getCantidad(), 1, 'C');
        $pdf->SetXY(180, $posY);
        $pdf->MultiCell(20, 5, "$" . $producto->getPrecio()*$producto->getCantidad(), 1, 'C');

    }

    function createLineDeuda($key, $deuda, $posY,$pdf)
    {

        $pdf->SetFont('Arial', '', 7);

        $pdf->SetXY(10, $posY);
        $pdf->MultiCell(20, 5, utf8_decode($key), 1, 'C');
        $pdf->SetXY(30, $posY);
        $pdf->MultiCell(110, 5, utf8_decode("Deuda"), 1, 'L');
        $pdf->SetXY(140, $posY);
        $pdf->MultiCell(20, 5, "$" . utf8_decode($deuda), 1, 'C');
        $pdf->SetXY(160, $posY);
        $pdf->MultiCell(20, 5, utf8_decode(""), 1, 'C');
        $pdf->SetXY(180, $posY);
        $pdf->MultiCell(20, 5, utf8_decode(""), 1, 'C');

    }

    function createBlackLineTable($pdf,$posY)
    {
        $pdf->SetFont('Arial', '', 7);

        $pdf->SetXY(10, $posY);
        $pdf->MultiCell(20, 5, '', 1, 'C');
        $pdf->SetXY(30, $posY);
        $pdf->MultiCell(110, 5, '', 1, 'L');
        $pdf->SetXY(140, $posY);
        $pdf->MultiCell(20, 5, '', 1, 'L');
        $pdf->SetXY(160, $posY);
        $pdf->MultiCell(20, 5, '', 1, 'C');
        $pdf->SetXY(180, $posY);
        $pdf->MultiCell(20, 5, '', 1, 'C');
    }

    function createRetirementTable($posY,$pdf)
    {
        $posY += 5;
        $pdf->SetFont('Arial', '', 7);

        $pdf->SetXY(10, $posY);
        $pdf->MultiCell(150, 20, "", 0, 'C');
        $pdf->SetXY(150, $posY);
        $pdf->MultiCell(50, 20, '', 1, 'C');
        $pdf->SetXY(150, $posY + 8);
        $pdf->MultiCell(50, 20, 'Firma:', 0, 'C');


    }

    function newPage($pdf)
    {

        $pdf->AddPage();

        //$this->Image($_SERVER['DOCUMENT_ROOT'].'/includes/images/iso/9105078191_139737_T.jpg', 15, 100, 180, 90);

        $pdf->SetFont('Arial', 'B', 8);

        $pdf->SetXY(10, 56);
        $pdf->MultiCell(0, 10, utf8_decode('ITEMS'), 0, '');
        $pdf->SetXY(10, 63);
        $pdf->MultiCell(20, 5, utf8_decode('Código'), 1, 'C');
        $pdf->SetXY(30, 63);
        $pdf->MultiCell(110, 5, utf8_decode('Nombre'), 1, 'L');
        $pdf->SetXY(140, 63);
        $pdf->MultiCell(20, 5, utf8_decode('Precio Unit.'), 1, 'L');
        $pdf->SetXY(160, 63);
        $pdf->MultiCell(20, 5, utf8_decode('Cantidad'), 1, 'L');
        $pdf->SetXY(180, 63);
        $pdf->MultiCell(20, 5, utf8_decode('Total'), 1, 'L');

    }
}