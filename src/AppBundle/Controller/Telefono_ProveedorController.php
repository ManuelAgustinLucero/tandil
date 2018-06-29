<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Telefono_Proveedor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Telefono_proveedor controller.
 *
 * @Route("admin/telefono_proveedor")
 */
class Telefono_ProveedorController extends Controller
{
    /**
     * Lists all telefono_Proveedor entities.
     *
     * @Route("/{id}", name="admin_telefono_proveedor_index")
     * @Method("GET")
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $telefono_Proveedors = $em->getRepository('AppBundle:Telefono_Proveedor')->findByProveedor($id);

        return $this->render('telefono_proveedor/index.html.twig', array(
            'telefono_Proveedors' => $telefono_Proveedors,
            'id'=>$id
        ));
    }

    /**
     * Creates a new telefono_Proveedor entity.
     *
     * @Route("/new/{id}", name="admin_telefono_proveedor_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();

        $proveedor = $em->getRepository('AppBundle:Proveedor')->find($id);

        $telefono_Proveedor = new Telefono_proveedor();
        $form = $this->createForm('AppBundle\Form\Telefono_ProveedorType', $telefono_Proveedor);
        $form->get('proveedor')->setData($proveedor);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($telefono_Proveedor);
            $em->flush();
            $this->addFlash(
                'notice',
                'Telefono agregado!'

            );
            return $this->redirectToRoute('admin_telefono_proveedor_new', array('id' => $id));
        }

        return $this->render('telefono_proveedor/new.html.twig', array(
            'telefono_Proveedor' => $telefono_Proveedor,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a telefono_Proveedor entity.
     *
     * @Route("/{id}", name="admin_telefono_proveedor_show")
     * @Method("GET")
     */
    public function showAction(Telefono_Proveedor $telefono_Proveedor)
    {
        $deleteForm = $this->createDeleteForm($telefono_Proveedor);

        return $this->render('telefono_proveedor/show.html.twig', array(
            'telefono_Proveedor' => $telefono_Proveedor,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing telefono_Proveedor entity.
     *
     * @Route("/{id}/edit", name="admin_telefono_proveedor_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Telefono_Proveedor $telefono_Proveedor)
    {
        $deleteForm = $this->createDeleteForm($telefono_Proveedor);
        $editForm = $this->createForm('AppBundle\Form\Telefono_ProveedorType', $telefono_Proveedor);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_telefono_proveedor_edit', array('id' => $telefono_Proveedor->getId()));
        }

        return $this->render('telefono_proveedor/edit.html.twig', array(
            'telefono_Proveedor' => $telefono_Proveedor,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a telefono_Proveedor entity.
     *
     * @Route("/{id}/{idreturn}", name="admin_telefono_proveedor_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteAction($id,$idreturn)
    {
        $em = $this->getDoctrine()->getManager();

        $telefono_Proveedors = $em->getRepository('AppBundle:Telefono_Proveedor')->find($id);


            $em->remove($telefono_Proveedors);
            $em->flush();

        return $this->redirectToRoute('admin_telefono_proveedor_index', array('id' => $idreturn));
    }

    /**
     * Creates a form to delete a telefono_Proveedor entity.
     *
     * @param Telefono_Proveedor $telefono_Proveedor The telefono_Proveedor entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Telefono_Proveedor $telefono_Proveedor)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_telefono_proveedor_delete', array('id' => $telefono_Proveedor->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
