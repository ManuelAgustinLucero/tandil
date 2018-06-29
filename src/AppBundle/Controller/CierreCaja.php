<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 19/06/18
 * Time: 11:46
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
class CierreCaja extends Controller
{
    /**
     * Creates a new cliente entity.
     *
     * @Route("/pdfCierreCaja/{anio}/{mes}/{dia}", name="pdfCierreCaja")
     * @Method({"GET", "POST"})
     */
    public function pdfCierreCajaAction(Request $request,$anio,$mes,$dia)
    {
//        $anio= $request->request->get('anio');
//        $dia= $request->request->get('dia');
//        $mes= $request->request->get('mes');

        $em = $this->getDoctrine()->getManager();
        //Compras del dia

        $db = $em->getConnection();
        $query ="SELECT compra.*,producto.titulo FROM compra INNER join producto on producto.id=compra.producto_id WHERE YEAR(compra.fechaRegistro)=$anio and MONTH(compra.fechaRegistro)=$mes and DAY(compra.fechaRegistro)=$dia";

        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);
        $compras=$stmt->fetchAll();


        //ventas del dia
        $db = $em->getConnection();
        $query ="SELECT detalle_venta.*,producto.titulo FROM detalle_venta inner join producto on producto.id=detalle_venta.producto_id WHERE YEAR(fecha)=$anio and MONTH(fecha)=$mes and DAY(fecha)=$dia";

        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);
        $ventas=$stmt->fetchAll();
//
//        $detalleVenta= $em->getRepository('AppBundle:DetalleVenta')->findBy( ['fecha' => getdate()] );
//        var_dump($detalleVenta);

        $pdf = new \FPDF();

        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);


        $pdf->SetFont('Arial', '', 12);

        $pdf->MultiCell(190, 16, '', 1, 'C');

        $pdf->Image('assets/img/logo.jpeg', 10, 10, 30);


        $pdf->MultiCell(0, 10, '', 1, 'C');
        $pdf->SetXY(15, 30);
        $pdf->MultiCell(0, 5, 'Fecha: ' . ($dia."/".$mes."/".$anio), 0, '');




        $pdf->SetFont('Arial', 'B', 9);

        $pdf->SetXY(10, 35);
        $pdf->MultiCell(0, 10, utf8_decode('ITEMS'), 0, '');
        $pdf->SetXY(10, 43);
        $pdf->MultiCell(130, 6, utf8_decode('Descripcion'), 1, 'C');
        $pdf->SetXY(140, 43);
        $pdf->MultiCell(60, 6, utf8_decode(' Total'), 1, 'C');


        $posY = 50;

//        Original
//        $this->createBlackLineTable($pdf, $posY);


        $cant = 0;
        $negativo=0;
        foreach ($compras as $key => $compra) {
            if ($cant > 36) {
                $this->newPage($pdf);
                $cant = 0;
                $posY = 65;
            }
            $this->createLineTable($key, $compra, $posY, $pdf);
            $posY += 5;
            $cant++;
            $negativo=$negativo+($compra["cantidad"]*$compra["precioCompra"]);

        }
        $cant = 0;
        $positivo=0;
        foreach ($ventas as $key => $venta) {
            if ($cant > 36) {
                $this->newPage($pdf);
                $cant = 0;
                $posY = 65;
            }
            $this->createVentaLineTable($key, $venta, $posY, $pdf);
            $posY += 5;
            $cant++;
            $positivo=$positivo+($venta["precio"]*$venta["cantidad"]);

        }

        $this->createLineTotal(($positivo-$negativo), $posY, $pdf);

        return new Response($pdf->Output(), 200, array(
            'Content-Type' => 'application/pdf'));
    }
    function createLineTotal($total, $posY,$pdf)
    {
        $pdf->SetFont('Arial', '',11);
        $pdf->SetXY(10, $posY+2);
        $pdf->MultiCell(130, 6, "Total:", 1, 'C');
        $pdf->SetXY(140, $posY+2);
        $pdf->MultiCell(60, 6, "$" . $total, 1, 'C');
    }

    function createLineTable($key, $compra, $posY,$pdf)
    {

        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY(10, $posY);
        $pdf->MultiCell(130, 6, ("Compra-".$compra["titulo"]." x ".$compra["cantidad"]), 1, 'C');
        $pdf->SetXY(140, $posY);
        $pdf->MultiCell(60, 6, "-$".$compra["precioCompra"]*$compra["cantidad"], 1, 'C');

    }
    function createVentaLineTable($key, $venta, $posY,$pdf)
    {

        $pdf->SetFont('Arial', '', 10);

        $pdf->SetXY(10, $posY+1);
        $pdf->MultiCell(130, 6, ("Venta-".$venta["titulo"]." x ".$venta["cantidad"]), 1, 'C');
        $pdf->SetXY(140, $posY+1);
        $pdf->MultiCell(60, 6, "+$".$venta["precio"]*$venta["cantidad"], 1, 'C');

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