<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 27/06/18
 * Time: 16:12
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
class CierreProducto extends Controller
{
    /**
     * Creates a new cliente entity.
     *
     * @Route("/pdfCierreProducto/{anio}/{mes}/{dia}", name="pdfCierreProducto")
     * @Method({"GET", "POST"})
     */
    public function pdfCierreProductoAction(Request $request,$anio,$mes,$dia)
    {


        $em = $this->getDoctrine()->getManager();
        //Compras del dia

        $db = $em->getConnection();
        $query ="SELECT producto.id,producto.titulo,SUM(detalle_venta.precio * detalle_venta.cantidad) as totalVenta from detalle_venta INNER JOIN producto on detalle_venta.producto_id=producto.id  WHERE YEAR(detalle_venta.fecha)= $anio and MONTH(detalle_venta.fecha)=$mes and DAY(detalle_venta.fecha)=$dia GROUP BY (producto.id)";

        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);
        $ventas=$stmt->fetchAll();


        //ventas del dia
        $db = $em->getConnection();
        $query ="SELECT producto.id,producto.titulo,SUM(compra.precioCompra * compra.cantidad) as totalCompra from compra INNER JOIN producto on compra.producto_id=producto.id WHERE YEAR(compra.fechaRegistro)= $anio and MONTH(compra.fechaRegistro)=$mes and DAY(compra.fechaRegistro)=$dia GROUP BY (producto.id)";

        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);
        $compras=$stmt->fetchAll();



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

        $pdf->SetXY(10, 43);
        $pdf->MultiCell(0, 10, utf8_decode('ITEMS'), 0, '');
        $pdf->SetXY(10, 53);
        $pdf->MultiCell(110, 5, utf8_decode('Nombre'), 1, 'C');
        $pdf->SetXY(120, 53);
        $pdf->MultiCell(30, 5, utf8_decode('Total Compra'), 1, 'L');
        $pdf->SetXY(150, 53);
        $pdf->MultiCell(30, 5, utf8_decode('Total Venta'), 1, 'L');
        $pdf->SetXY(180, 53);
        $pdf->MultiCell(20, 5, utf8_decode('Total'), 1, 'L');

        $posY = 59;

        $cant = 0;
        $total=0;
        foreach ($ventas as $venta){
            if ($cant > 36) {
                $this->newPage($pdf);
                $cant = 0;
                $posY = 65;
            }
            $totalVentaCompra=$venta["totalVenta"];
            $totalCompra=0;
            foreach ($compras as $compra){
                if ($venta["id"]==$compra["id"]){
                    $totalVentaCompra= $venta["totalVenta"] - $compra["totalCompra"];
                    $totalCompra=$compra["totalCompra"];
                }
            }
            $this->createLineTable($venta["titulo"],$totalCompra,$venta["totalVenta"],$totalVentaCompra, $posY, $pdf);
            $posY += 5;
            $cant++;
            $total=$total+$totalVentaCompra;
        }

        $this->createLineTotal(($total), $posY, $pdf);

        return new Response($pdf->Output(), 200, array(
            'Content-Type' => 'application/pdf'));
    }
    function createLineTotal($total, $posY,$pdf)
    {
        $pdf->SetFont('Arial', '',11);
        $pdf->SetXY(10, $posY+2);
        $pdf->MultiCell(170, 6, "Total:", 1, 'C');
        $pdf->SetXY(180, $posY+2);
        $pdf->MultiCell(20, 6, "$" . $total, 1, 'L');
    }

    function createLineTable($producto, $totalVenta,$totalCompra,$totalVentaCompra, $posY,$pdf)
    {

        $pdf->SetFont('Arial', '', 10);


        $pdf->SetXY(10, $posY);
        $pdf->MultiCell(110, 5, $producto, 1, 'C');
        $pdf->SetXY(120, $posY);
        $pdf->MultiCell(30, 5,"$ ". $totalVenta, 1, 'L');
        $pdf->SetXY(150, $posY);
        $pdf->MultiCell(30, 5,"$ ". $totalCompra, 1, 'L');
        $pdf->SetXY(180,$posY);
        $pdf->MultiCell(20, 5, "$ ".$totalVentaCompra, 1, 'L');


    }




    function newPage($pdf)
    {

        $pdf->AddPage();


        $pdf->SetFont('Arial', 'B', 8);

        $pdf->SetXY(10, 43);
        $pdf->MultiCell(0, 10, utf8_decode('ITEMS'), 0, '');
        $pdf->SetXY(10, 53);
        $pdf->MultiCell(110, 5, utf8_decode('Nombre'), 1, 'C');
        $pdf->SetXY(120, 53);
        $pdf->MultiCell(30, 5, utf8_decode('Total Compra'), 1, 'L');
        $pdf->SetXY(150, 53);
        $pdf->MultiCell(30, 5, utf8_decode('Total Venta'), 1, 'L');
        $pdf->SetXY(180, 53);
        $pdf->MultiCell(20, 5, utf8_decode('Total'), 1, 'L');

    }
}