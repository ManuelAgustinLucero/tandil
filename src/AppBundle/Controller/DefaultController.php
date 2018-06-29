<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need

        if ($this->getUser()){
            $em = $this->getDoctrine()->getManager();

            $productos = $em->getRepository('AppBundle:Producto')->findAll();

            return $this->render('default/index.html.twig',array(
                'productos' => $productos,
            ));
        }else{
            return $this->redirectToRoute('fos_user_security_login');

        }
    }
    /**
     * Creates a new cliente entity.
     *
     * @Route("/graficosProductosVentas", name="grafico_producto_ventas")
     * @Method({"GET", "POST"})
     */
    public function graficosProductosVentasAction(Request $request)
    {
        $idProducto= $request->request->get('idProducto');
        $anio= $request->request->get('anio');



        $em = $this->getDoctrine()->getManager();
        $db = $em->getConnection();
        $query ="SELECT COUNT(*) AS contador,  MONTH(fecha) AS mes,SUM(precio * cantidad) as total,SUM(cantidad) as cantidad FROM detalle_venta WHERE YEAR(fecha)= $anio and detalle_venta.producto_id=$idProducto GROUP BY MONTH(fecha) ORDER BY MONTH(fecha) ASC";

        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);
        $products=$stmt->fetchAll();
        return new JsonResponse($products);


    }
    /**
     * Creates a new cliente entity.
     *
     * @Route("/graficosVentasMes", name="grafico_ventas_mes")
     * @Method({"GET", "POST"})
     */
    public function graficosVentasMesAction(Request $request)
    {
        $mes= $request->request->get('mes');
        $anio= $request->request->get('anio');



        $em = $this->getDoctrine()->getManager();
        $db = $em->getConnection();
        $query ="SELECT COUNT(*) AS contador,  MONTH(fecha) AS mes,SUM(venta.total) as total FROM venta WHERE YEAR(fecha)= $anio and MONTH(fecha)=$mes GROUP BY MONTH(fecha) ORDER BY MONTH(fecha) ASC";

        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);
        $products=$stmt->fetchAll();
        return new JsonResponse($products);


    }
    /**
     * Creates a new cliente entity.
     *
     * @Route("/graficosComprasMes", name="grafico_compras_mes")
     * @Method({"GET", "POST"})
     */
    public function graficosComprasMesAction(Request $request)
    {
        $mes= $request->request->get('mes');
        $anio= $request->request->get('anio');



        $em = $this->getDoctrine()->getManager();
        $db = $em->getConnection();
        $query ="SELECT COUNT(*) AS contador,  MONTH(fechaRegistro) AS mes,SUM(compra.precioCompra * compra.cantidad) as total FROM compra WHERE YEAR(fechaRegistro)= $anio and MONTH(fechaRegistro)=$mes GROUP BY MONTH(fechaRegistro) ORDER BY MONTH(fechaRegistro) ASC";

        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);
        $products=$stmt->fetchAll();
        return new JsonResponse($products);


    }
    /**
     * Creates a new cliente entity.
     *
     * @Route("/graficosVentasDia", name="grafico_ventas_dia")
     * @Method({"GET", "POST"})
     */
    public function graficosVentasDiaAction(Request $request)
    {
        $mes= $request->request->get('mes');
        $anio= $request->request->get('anio');
        $day= $request->request->get('dia');

        $em = $this->getDoctrine()->getManager();
        $db = $em->getConnection();
        $query ="SELECT COUNT(*) AS contador,  MONTH(fecha) AS mes,SUM(venta.total) as total FROM venta WHERE YEAR(fecha)= $anio and MONTH(fecha)=$mes and  DAY(fecha)=$day GROUP BY MONTH(fecha) ORDER BY MONTH(fecha) ASC";

        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);
        $products=$stmt->fetchAll();
        return new JsonResponse($products);


    }
    /**
     * Creates a new cliente entity.
     *
     * @Route("/graficosComprasDia", name="grafico_compras_dia")
     * @Method({"GET", "POST"})
     */
    public function graficosComprasDiaAction(Request $request)
    {
        $mes= $request->request->get('mes');
        $anio= $request->request->get('anio');
        $day= $request->request->get('dia');

        $em = $this->getDoctrine()->getManager();
        $db = $em->getConnection();
        $query ="SELECT COUNT(*) AS contador,  MONTH(fechaRegistro) AS mes,SUM(compra.precioCompra * compra.cantidad) as total FROM compra WHERE YEAR(fechaRegistro)= $anio and MONTH(fechaRegistro)=$mes and DAY(fechaRegistro)=$day GROUP BY MONTH(fechaRegistro) ORDER BY MONTH(fechaRegistro) ASC";

        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);
        $products=$stmt->fetchAll();
        return new JsonResponse($products);


    }
    /**
     * Creates a new cliente entity.
     *
     * @Route("/venta/fechaVenta/{fecha}", name="fechaVenta")
     * @Method({"GET", "POST"})
     */
    public function fechaVentaAction(Request $request,$fecha)
    {
        $f=new \DateTime($fecha);
        $f1=$f->format('Y-m-d 00:00:00');
        $f2=$f->format('Y-m-d 23:59:00');


        $ventas = $this->getDoctrine()
            ->getManager()
            ->createQuery("SELECT e FROM AppBundle:Venta e WHERE e.fecha BETWEEN '$f1' AND '$f2'")
            ->getResult();

        return $this->render('venta/index.html.twig', array(
            'ventas' => $ventas,

        ));

    }
}
