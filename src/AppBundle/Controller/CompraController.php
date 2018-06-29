<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Compra;
use AppBundle\Entity\Stock;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Compra controller.
 *
 * @Route("admin/compra")
 */
class CompraController extends Controller
{
    /**
     * Lists all compra entities.
     *
     * @Route("/", name="admin_compra_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $compras = $em->getRepository('AppBundle:Compra')->findAll();

        return $this->render('compra/index.html.twig', array(
            'compras' => $compras,
        ));
//        $f=new \DateTime('now');
//        $f1=$f->format('Y-m-d 00:00:00');
//        $f2=$f->format('Y-m-d 23:59:00');
//
//        $compras = $this->getDoctrine()
//            ->getManager()
//            ->createQuery("SELECT e FROM AppBundle:Compra e WHERE e.fechaRegistro BETWEEN '$f1' AND '$f2'")
//            ->getResult();
//        return $this->render('compra/index.html.twig', array(
//            'compras' => $compras,
//        ));
    }

    /**
     * Creates a new compra entity.
     *
     * @Route("/new", name="admin_compra_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $compra = new Compra();
        $form = $this->createForm('AppBundle\Form\CompraType', $compra);
        $form->get('fechaRegistro')->setData( new \DateTime('now'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($compra);
            $em->flush();
            $stock= $em->getRepository('AppBundle:Stock')->findOneByProducto( $compra->getProducto()->getId());

            if ($stock){
                $stock->setCantidad( $stock->getCantidad()+$compra->getCantidad());
                $stock->setPrecioCosto($compra->getPrecioCompra());
//                $stock->setPrecioVenta($compra->getPrecioVenta());
//                $stock->setPrecioVentaDescuento($compra->getPrecioVentaDescuento());
                $stock->setFechaActulizacion( new \DateTime('now'));

                $stock->setCodigo("58585");
                $em->persist($stock);
                $em->flush();

                $this->addFlash(
                    'notice',
                    'Compra cargada con exito, stock actualizado!'

                );
//                return $this->redirectToRoute('admin_compra_new');

            }else{
                $stockNew= new Stock();
                $stockNew->setProducto($compra->getProducto());
                $stockNew->setCantidad($compra->getCantidad());
                $stockNew->setFecha( new \DateTime('now'));
                $stockNew->setFechaActulizacion( new \DateTime('now'));
                $stockNew->setPrecioCosto($compra->getPrecioCompra());
//                $stockNew->setPrecioVenta($compra->getPrecioVenta());
//                $stockNew->setPrecioVentaDescuento($compra->getPrecioVentaDescuento());
                $stockNew->setCodigo("5852");

                $em->persist($stockNew);
                $em->flush();
                $this->addFlash(
                    'notice',
                    'Compra cargada con exito!'

                );
//                return $this->redirectToRoute('admin_compra_new');

            }

        }

        return $this->render('compra/new.html.twig', array(
            'compra' => $compra,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a compra entity.
     *
     * @Route("/{id}", name="admin_compra_show")
     * @Method("GET")
     */
    public function showAction(Compra $compra)
    {
        $deleteForm = $this->createDeleteForm($compra);

        return $this->render('compra/show.html.twig', array(
            'compra' => $compra,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing compra entity.
     *
     * @Route("/{id}/edit", name="admin_compra_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Compra $compra)
    {
        $deleteForm = $this->createDeleteForm($compra);
        $editForm = $this->createForm('AppBundle\Form\CompraType', $compra);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_compra_edit', array('id' => $compra->getId()));
        }

        return $this->render('compra/edit.html.twig', array(
            'compra' => $compra,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a compra entity.
     *
     * @Route("/{id}", name="admin_compra_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Compra $compra)
    {
        $form = $this->createDeleteForm($compra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($compra);
            $em->flush();
        }

        return $this->redirectToRoute('admin_compra_index');
    }

    /**
     * Creates a form to delete a compra entity.
     *
     * @param Compra $compra The compra entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Compra $compra)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_compra_delete', array('id' => $compra->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
