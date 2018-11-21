<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DetalleVenta;
use AppBundle\Entity\Venta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ventum controller.
 *
 * @Route("admin/venta")
 */
class VentaController extends Controller
{
    /**
     * Lists all ventum entities.
     *
     * @Route("/", name="admin_venta_index")
     * @Method("GET")
     */
    public function indexAction()
    {

        $f=new \DateTime('now');
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

    /**
     * Creates a new ventum entity.
     *
     * @Route("/new", name="admin_venta_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $clientes = $em->getRepository('AppBundle:Cliente')->findAll();
        $stock = $em->getRepository('AppBundle:Stock')->findAll();

        return $this->render('venta/new.html.twig', array(

            'clientes'=>$clientes,
            'stocks'=>$stock

        ));


    }

    /**
     * Finds and displays a ventum entity.
     *
     * @Route("/{id}", name="admin_venta_show")
     * @Method("GET")
     */
    public function showAction(Venta $ventum)
    {
        $deleteForm = $this->createDeleteForm($ventum);

        return $this->render('venta/show.html.twig', array(
            'ventum' => $ventum,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ventum entity.
     *
     * @Route("/{id}/edit", name="admin_venta_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Venta $ventum)
    {

        $em = $this->getDoctrine()->getManager();

        $clientes = $em->getRepository('AppBundle:Cliente')->findAll();
        $stock = $em->getRepository('AppBundle:Stock')->findAll();
        $detalleVenta = $em->getRepository('AppBundle:DetalleVenta')->findByVenta($ventum->getId());

        return $this->render('venta/edit.html.twig', array(
            'clientes'=>$clientes,
            'stocks'=>$stock,
            'detalleVenta'=>$detalleVenta,
            'id'=>$ventum->getId(),
            'venta'=>$ventum
        ));
    }

    /**
     * Deletes a ventum entity.
     *
     * @Route("/eliminar/{id}", name="admin_venta_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Venta $ventum)
    {
        $em = $this->getDoctrine()->getManager();

        $detalleVenta = $em->getRepository('AppBundle:DetalleVenta')->findByVenta($ventum->getId());

        foreach ($detalleVenta as $detaventa){

            $stock= $em->getRepository('AppBundle:Stock')->findOneByProducto( $detaventa->getProducto()->getId());
            $stock->setCantidad($stock->getCantidad() + $detaventa->getCantidad());
            $em->persist($stock);
            $em->flush();
            $em->remove($detaventa);
            $em->flush();
        }


        $em->remove($ventum);
        $em->flush();


        return $this->redirectToRoute('admin_venta_index');
    }

    /**
     * Creates a form to delete a ventum entity.
     *
     * @param Venta $ventum The ventum entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Venta $ventum)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_venta_delete', array('id' => $ventum->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Creates a new ventum entity.
     *
     * @Route("/getStock", name="admin_venta_getstock")
     * @Method({"GET", "POST"})
     */
    public function getStockAction(Request $request)
    {

//        $repository = $this->getDoctrine()->getRepository('AppBundle:Stock');

//        $query = $repository->createQueryBuilder('p')
////            ->orderBy('p.price', 'ASC')
//            ->getQuery();
//
//        $products = $query->getResult();
        $id = $request->request->get('idStock');

        $em = $this->getDoctrine()->getManager();
        $db = $em->getConnection();
        $query ="select stock.*,producto.titulo,producto.codigo,producto.importe,tipo_unidad.tipo from stock inner  join producto on stock.producto_id=producto.id inner join tipo_unidad on tipo_unidad.id=producto.tipoUnidad_id where stock.id= $id";

        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);
        $products=$stmt->fetchAll();
        return new JsonResponse($products);



    }
    /**
     * Creates a new cliente entity.
     *
     * @Route("/altaVenta", name="admin_venta_altaVenta")
     * @Method({"GET", "POST"})
     */
    public function altaVentaAction(Request $request)
    {
        $idCliente= $request->request->get('idCliente');
        $productoVenta= $request->request->get('productoVenta');
        $total= $request->request->get('total');
        $totalComision= $request->request->get('totalComision');

        $em = $this->getDoctrine()->getManager();

        $cliente = $em->getRepository('AppBundle:Cliente')->find($idCliente);

        $venta =new Venta();
        $venta->setCliente($cliente);
        $venta->setTotal($total);
        $venta->setTotalComision($totalComision);
        $venta->setFecha(new \DateTime('now'));
        $em->persist($venta);
        $em->flush();

        foreach ($productoVenta as $prodVent){

            $stock= $em->getRepository('AppBundle:Stock')->find( $prodVent["idStock"]);
            $stock->setCantidad($stock->getCantidad() - $prodVent["cantidad"]);
            $em->persist($stock);
            $em->flush();

            $producto = $em->getRepository('AppBundle:Producto')->find($prodVent["idProducto"]);
            $venta = $em->getRepository('AppBundle:Venta')->find($venta->getId());
            $productoVentaNew= new DetalleVenta();
            $productoVentaNew->setProducto($producto);
            $productoVentaNew->setVenta($venta);
            $productoVentaNew->setPrecio($prodVent["precioUnidad"]);
            $productoVentaNew->setCantidad($prodVent["cantidad"]);
            $productoVentaNew->setComision($prodVent["precioComision"]);
            $productoVentaNew->setFecha( new \DateTime('now'));

            $em->persist($productoVentaNew);
            $em->flush();

        }

        return new JsonResponse($venta->getId());

    }

    /**
     * Deletes a ventum entity.
     *
     * @Route("/deleteDetalleVenta/{id}", name="admin_detalleVenta_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteDetalleVentaAction(Request $request, DetalleVenta $detalleVenta)
    {
        $em = $this->getDoctrine()->getManager();
        $stock= $em->getRepository('AppBundle:Stock')->findOneByProducto( $detalleVenta->getProducto()->getId());

        $stock->setCantidad($stock->getCantidad() + $detalleVenta->getCantidad());
        $em->persist($stock);
        $em->flush();
        $venta =$em->getRepository('AppBundle:Venta')->find( $detalleVenta->getVenta()->getId());
        $venta->setTotal($venta->getTotal()-$detalleVenta->getPrecio() * $detalleVenta->getCantidad());
        $venta->setTotalComision($venta->getTotalComision()-$detalleVenta->getComision() );
        $em->persist($venta);
        $em->flush();
        $em->remove($detalleVenta);
        $em->flush();
        return $this->redirectToRoute('admin_venta_edit', array('id' => $detalleVenta->getVenta()->getId()));

    }
    /**
     * Deletes a ventum entity.
     *
     * @Route("/agragarDetalleVenta", name="admin_detalleVenta_agregar")
     * @Method({"GET", "POST"})
     */
    public function agregarDetalleVentaAction(Request $request )
    {

        $idVenta= $request->request->get('idVenta');
        $idProducto= $request->request->get('productoId');
        $precioUnitario= $request->request->get('precioUnitario');
        $precioComision= $request->request->get('precioComision');

        $cantidad= $request->request->get('cantidad');
        $idStock= $request->request->get('idStock');

        $em = $this->getDoctrine()->getManager();


        $venta = $em->getRepository('AppBundle:Venta')->find($idVenta);
        $venta->setTotal($venta->getTotal()+$precioUnitario * $cantidad);
        $venta->setTotalComision($venta->getTotalComision()+$precioComision);

        $em->persist($venta);
        $em->flush();

        $producto = $em->getRepository('AppBundle:Producto')->find($idProducto);


        $stock= $em->getRepository('AppBundle:Stock')->find( $idStock);
        $stock->setCantidad($stock->getCantidad() - $cantidad);
        $em->persist($stock);
        $em->flush();

        $productoVentaNew= new DetalleVenta();
        $productoVentaNew->setProducto($producto);
        $productoVentaNew->setVenta($venta);
        $productoVentaNew->setPrecio($precioUnitario);
        $productoVentaNew->setComision($precioComision);
        $productoVentaNew->setCantidad($cantidad);
        $productoVentaNew->setFecha( new \DateTime('now'));

        $em->persist($productoVentaNew);
        $em->flush();
        return new JsonResponse("success");

    }
    /**
     * Deletes a ventum entity.
     *
     * @Route("/updateClienteDetalleVenta", name="admin_detalleVenta_updateClienteDetalleVenta")
     * @Method({"GET", "POST"})
     */
    public function updateClienteDetalleVentaAction(Request $request )
    {

        $idCliente= $request->request->get('idCliente');
        $idVenta= $request->request->get('idVenta');


        $em = $this->getDoctrine()->getManager();


        $venta = $em->getRepository('AppBundle:Venta')->find($idVenta);
        $cliente = $em->getRepository('AppBundle:Cliente')->find($idCliente);
        $venta->setCliente($cliente);
        $em->persist($venta);
        $em->flush();


        return new JsonResponse("success");

    }
}
