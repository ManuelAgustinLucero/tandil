<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cliente;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cliente controller.
 *
 * @Route("admin/cliente")
 */
class ClienteController extends Controller
{
    /**
     * Lists all cliente entities.
     *
     * @Route("/", name="admin_cliente_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $clientes = $em->getRepository('AppBundle:Cliente')->findAll();

        return $this->render('cliente/index.html.twig', array(
            'clientes' => $clientes,
        ));
    }

    /**
     * Creates a new cliente entity.
     *
     * @Route("/new", name="admin_cliente_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $cliente = new Cliente();
        $form = $this->createForm('AppBundle\Form\ClienteType', $cliente);
        $form->get('fechaRegistro')->setData( new \DateTime('now'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cliente);
            $em->flush();

            return $this->redirectToRoute('admin_cliente_show', array('id' => $cliente->getId()));
        }

        return $this->render('cliente/new.html.twig', array(
            'cliente' => $cliente,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a cliente entity.
     *
     * @Route("/{id}", name="admin_cliente_show")
     * @Method("GET")
     */
    public function showAction(Cliente $cliente)
    {
        $deleteForm = $this->createDeleteForm($cliente);

        return $this->render('cliente/show.html.twig', array(
            'cliente' => $cliente,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing cliente entity.
     *
     * @Route("/{id}/edit", name="admin_cliente_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Cliente $cliente)
    {
        $deleteForm = $this->createDeleteForm($cliente);
        $editForm = $this->createForm('AppBundle\Form\ClienteType', $cliente);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_cliente_edit', array('id' => $cliente->getId()));
        }

        return $this->render('cliente/edit.html.twig', array(
            'cliente' => $cliente,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a cliente entity.
     *
     * @Route("/{id}", name="admin_cliente_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Cliente $cliente)
    {
        $form = $this->createDeleteForm($cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cliente);
            $em->flush();
        }

        return $this->redirectToRoute('admin_cliente_index');
    }

    /**
     * Creates a form to delete a cliente entity.
     *
     * @param Cliente $cliente The cliente entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Cliente $cliente)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_cliente_delete', array('id' => $cliente->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Creates a new producto entity.
     *
     * @Route("/movimiento/", name="admin_cliente_moviminetos")
     * @Method({"GET", "POST"})
     */
    public function movimientosClienteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $clientes = $em->getRepository('AppBundle:Cliente')->findAll();

        return $this->render('movimientosCliente/movimientosCliente.html.twig', array(
            'clientes' => $clientes,
        ));
    }

    /**
     * Creates a new producto entity.
     *
     * @Route("/get/movimientoProducto/", name="admin_cliente_get_moviminetos")
     * @Method({"GET", "POST"})
     */
    public function getClienteMovimientosAction(Request $request)
    {
        $cliente = $request->request->get('producto');
        $fecha1 = $request->request->get('desde');
        $fecha2 = $request->request->get('hasta');

        $f1=new \DateTime($fecha1);
        $f2=new \DateTime($fecha2);

        $f1=$f1->format('Y-m-d 00:00:00');
        $f2=$f2->format('Y-m-d 23:59:00');



        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository("AppBundle:Venta");
        $query = $repository->createQueryBuilder('v')
            ->select(array(
                    'dv.cantidad',
                    'dv.precio',
                    'dv.fecha',
                    'p.titulo'
                )
            )
            ->innerJoin('AppBundle:DetalleVenta', 'dv', 'WITH', 'dv.venta = v.id')
            ->innerJoin('AppBundle:Producto', 'p', 'WITH', 'p.id = dv.producto')

            ->where('v.cliente = :clienteId')
            ->andWhere('v.fecha BETWEEN :desde AND :hasta')
            ->setParameter('desde', $f1)
            ->setParameter('hasta', $f2)
            ->setParameter('clienteId', $cliente)
            ->setMaxResults(10000)
        ;
        $detalle=$query->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);


        $query = $repository->createQueryBuilder('v')
            ->select(array(
                    'v',

                )
            )

            ->where('v.cliente = :clienteId')
            ->andWhere('v.fecha BETWEEN :desde AND :hasta')
            ->setParameter('desde', $f1)
            ->setParameter('hasta', $f2)
            ->setParameter('clienteId', $cliente)
            ->setMaxResults(10000)
        ;
        $ventas=$query->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $data=array(
            "detalleVenta"=>$detalle,
            "ventas"=>$ventas

        );
        return new JsonResponse($data);

    }

    /**
     * Creates a new producto entity.
     *
     * @Route("/get/movimientoProducto/detalle/", name="admin_cliente_get_moviminetos_detalle")
     * @Method({"GET", "POST"})
     */
    public function getClienteDetalleMovimientosAction(Request $request)
    {
        $id= $request->request->get('id');




        $em = $this->getDoctrine()->getManager();

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

            ->where('dv.venta = :id')
            ->setParameter('id', $id)
            ->setMaxResults(10000)
        ;
        $detalle=$query->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);


        $data=array(
            "detalleVenta"=>$detalle,
        );
        return new JsonResponse($data);

    }
}
