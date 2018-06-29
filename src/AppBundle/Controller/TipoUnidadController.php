<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TipoUnidad;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Tipounidad controller.
 *
 * @Route("admin/tipounidad")
 */
class TipoUnidadController extends Controller
{
    /**
     * Lists all tipoUnidad entities.
     *
     * @Route("/", name="admin_tipounidad_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tipoUnidads = $em->getRepository('AppBundle:TipoUnidad')->findAll();

        return $this->render('tipounidad/index.html.twig', array(
            'tipoUnidads' => $tipoUnidads,
        ));
    }

    /**
     * Creates a new tipoUnidad entity.
     *
     * @Route("/new", name="admin_tipounidad_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $tipoUnidad = new Tipounidad();
        $form = $this->createForm('AppBundle\Form\TipoUnidadType', $tipoUnidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tipoUnidad);
            $em->flush();

            return $this->redirectToRoute('admin_tipounidad_show', array('id' => $tipoUnidad->getId()));
        }

        return $this->render('tipounidad/new.html.twig', array(
            'tipoUnidad' => $tipoUnidad,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a tipoUnidad entity.
     *
     * @Route("/{id}", name="admin_tipounidad_show")
     * @Method("GET")
     */
    public function showAction(TipoUnidad $tipoUnidad)
    {
        $deleteForm = $this->createDeleteForm($tipoUnidad);

        return $this->render('tipounidad/show.html.twig', array(
            'tipoUnidad' => $tipoUnidad,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing tipoUnidad entity.
     *
     * @Route("/{id}/edit", name="admin_tipounidad_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TipoUnidad $tipoUnidad)
    {
        $deleteForm = $this->createDeleteForm($tipoUnidad);
        $editForm = $this->createForm('AppBundle\Form\TipoUnidadType', $tipoUnidad);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_tipounidad_edit', array('id' => $tipoUnidad->getId()));
        }

        return $this->render('tipounidad/edit.html.twig', array(
            'tipoUnidad' => $tipoUnidad,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a tipoUnidad entity.
     *
     * @Route("/{id}", name="admin_tipounidad_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TipoUnidad $tipoUnidad)
    {
        $form = $this->createDeleteForm($tipoUnidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tipoUnidad);
            $em->flush();
        }

        return $this->redirectToRoute('admin_tipounidad_index');
    }

    /**
     * Creates a form to delete a tipoUnidad entity.
     *
     * @param TipoUnidad $tipoUnidad The tipoUnidad entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TipoUnidad $tipoUnidad)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_tipounidad_delete', array('id' => $tipoUnidad->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
