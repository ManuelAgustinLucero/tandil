<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Familia;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Familium controller.
 *
 * @Route("admin/familia")
 */
class FamiliaController extends Controller
{
    /**
     * Lists all familium entities.
     *
     * @Route("/", name="admin_familia_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $familias = $em->getRepository('AppBundle:Familia')->findAll();

        return $this->render('familia/index.html.twig', array(
            'familias' => $familias,
        ));
    }

    /**
     * Creates a new familium entity.
     *
     * @Route("/new", name="admin_familia_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $familium = new Familia();
        $form = $this->createForm('AppBundle\Form\FamiliaType', $familium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($familium);
            $em->flush();

            return $this->redirectToRoute('admin_familia_show', array('id' => $familium->getId()));
        }

        return $this->render('familia/new.html.twig', array(
            'familium' => $familium,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a familium entity.
     *
     * @Route("/{id}", name="admin_familia_show")
     * @Method("GET")
     */
    public function showAction(Familia $familium)
    {
        $deleteForm = $this->createDeleteForm($familium);

        return $this->render('familia/show.html.twig', array(
            'familium' => $familium,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing familium entity.
     *
     * @Route("/{id}/edit", name="admin_familia_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Familia $familium)
    {
        $deleteForm = $this->createDeleteForm($familium);
        $editForm = $this->createForm('AppBundle\Form\FamiliaType', $familium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_familia_edit', array('id' => $familium->getId()));
        }

        return $this->render('familia/edit.html.twig', array(
            'familium' => $familium,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a familium entity.
     *
     * @Route("/{id}", name="admin_familia_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Familia $familium)
    {
        $form = $this->createDeleteForm($familium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($familium);
            $em->flush();
        }

        return $this->redirectToRoute('admin_familia_index');
    }

    /**
     * Creates a form to delete a familium entity.
     *
     * @param Familia $familium The familium entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Familia $familium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_familia_delete', array('id' => $familium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
