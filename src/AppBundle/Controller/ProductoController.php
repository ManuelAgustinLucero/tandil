<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Producto;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Producto controller.
 *
 * @Route("admin/producto")
 */
class ProductoController extends Controller
{
    /**
     * Lists all producto entities.
     *
     * @Route("/", name="admin_producto_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $productos = $em->getRepository('AppBundle:Producto')->findAll();

        return $this->render('producto/index.html.twig', array(
            'productos' => $productos,
        ));
    }

    /**
     * Creates a new producto entity.
     *
     * @Route("/new", name="admin_producto_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $producto = new Producto();
        $form = $this->createForm('AppBundle\Form\ProductoType', $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $productos = $em->getRepository('AppBundle:Producto')->findOneByCodigo($producto->getCodigo());
            if ($productos){
                $this->addFlash(
                    'notice',
                    'Producto con codigo ya existente!'

                );
            }else{
                $em->persist($producto);
                $em->flush();
                return $this->redirectToRoute('admin_producto_show', array('id' => $producto->getId()));
            }


        }

        return $this->render('producto/new.html.twig', array(
            'producto' => $producto,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a producto entity.
     *
     * @Route("/{id}", name="admin_producto_show")
     * @Method("GET")
     */
    public function showAction(Producto $producto)
    {
        $deleteForm = $this->createDeleteForm($producto);

        return $this->render('producto/show.html.twig', array(
            'producto' => $producto,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing producto entity.
     *
     * @Route("/{id}/edit", name="admin_producto_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Producto $producto)
    {
        $deleteForm = $this->createDeleteForm($producto);
        $editForm = $this->createForm('AppBundle\Form\ProductoType', $producto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_producto_edit', array('id' => $producto->getId()));
        }

        return $this->render('producto/edit.html.twig', array(
            'producto' => $producto,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a producto entity.
     *
     * @Route("/{id}", name="admin_producto_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Producto $producto)
    {
        $form = $this->createDeleteForm($producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($producto);
            $em->flush();
        }

        return $this->redirectToRoute('admin_producto_index');
    }

    /**
     * Creates a form to delete a producto entity.
     *
     * @param Producto $producto The producto entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Producto $producto)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_producto_delete', array('id' => $producto->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    /**
     * Creates a new producto entity.
     *
     * @Route("/movimiento/", name="admin_producto_moviminetos")
     * @Method({"GET", "POST"})
     */
    public function movimientosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $stock = $em->getRepository('AppBundle:Stock')->findAll();

        return $this->render('movimientosProducto/movimientoProducto.html.twig', array(
            'stocks' => $stock,
        ));
    }


    /**
     * Creates a new producto entity.
     *
     * @Route("/get/movimientoProducto/", name="admin_producto_get_moviminetos")
     * @Method({"GET", "POST"})
     */
    public function getMovimientosAction(Request $request)
    {
        $producto = $request->request->get('producto');
        $fecha1 = $request->request->get('desde');
        $fecha2 = $request->request->get('hasta');

        $f1=new \DateTime($fecha1);
        $f2=new \DateTime($fecha2);

        $f1=$f1->format('Y-m-d 00:00:00');
        $f2=$f2->format('Y-m-d 23:59:00');



        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("AppBundle:Compra");
        $query = $repository->createQueryBuilder('c')
            ->select(array(
                  'c.cantidad',
                    'c.precioCompra',
                    'c.fechaRegistro',
                    'p.titulo'
                )
            )
            ->innerJoin('AppBundle:Producto', 'p', 'WITH', 'p.id = c.producto')

            ->where('c.producto = :productoId')
            ->andWhere('c.fechaRegistro BETWEEN :desde AND :hasta')
            ->setParameter('desde', $f1)
            ->setParameter('hasta', $f2)
            ->setParameter('productoId', $producto)
            ->setMaxResults(10000)
        ;
        $compra=$query->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);


        $repository = $em->getRepository("AppBundle:DetalleVenta");
        $query = $repository->createQueryBuilder('dv')
            ->select(array(
                    'dv.cantidad',
                    'dv.precio',
                    'dv.fecha',
                    'p.titulo'

                )
            )
            ->innerJoin('AppBundle:Producto', 'p', 'WITH', 'p.id = dv.producto')
            ->where('dv.producto = :productoId')
            ->andWhere('dv.fecha BETWEEN :desde AND :hasta')
            ->setParameter('desde', $f1)
            ->setParameter('hasta', $f2)
            ->setParameter('productoId', $producto)
            ->setMaxResults(10000)
        ;
        $detalleVenta=$query->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);


        $repository = $em->getRepository("AppBundle:Stock");
        $query = $repository->createQueryBuilder('st')
            ->select(array(
                    'st.cantidad',
                    'st.fechaActulizacion',
                    'p.titulo',
                    'pr.nombre'
                )
            )
            ->innerJoin('AppBundle:Producto', 'p', 'WITH', 'p.id = st.producto')
            ->innerJoin('AppBundle:Proveedor', 'pr', 'WITH', 'pr.id = st.proveedor')

            ->where('st.producto = :productoId')
            ->setParameter('productoId', $producto)
            ->setMaxResults(10000)
        ;
        $stock=$query->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);


        $data=array(
            "compra"=>$compra,
            "detalleVenta"=>$detalleVenta,
            "stock"=>$stock
        );
        return new JsonResponse($data);

    }
}
