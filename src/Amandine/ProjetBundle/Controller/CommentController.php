<?php

namespace Amandine\ProjetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Amandine\ProjetBundle\Entity\Comment;
use Amandine\ProjetBundle\Form\CommentType;
use Amandine\ProjetBundle\Entity\Product;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Comment controller.
 */
class CommentController extends Controller
{
    public function newAction($id)
    {
        $product = $this->getProduct($id);
        $user = $this->getUser($id);

        $comment = new Comment();
        $comment->setProduct($product);
        $comment->setUser($user);
        $form   = $this->createForm(CommentType::class, $comment);

        return $this->render('AmandineProjetBundle:Comment:form.html.twig', array(
            'comment' => $comment,
            'form'   => $form->createView()
        ));
    }

    public function createAction(Request $request, $id)
    {
        $product = $this->getProduct($id);
        $user = $this->getUser($id);

        $comment  = new Comment();
        $comment->setProduct($product);
        $comment->setUser($user);
        /*$request = $this->getRequest();*/
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // TODO: Persist the comment entity
            $em = $this->getDoctrine()
                       ->getEntityManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirect($this->generateUrl('amandine_projet_produit', array(
                'id' => $comment->getProduct()->getId())) .
                '#comment-' . $comment->getId()
            );
        }

        return $this->render('AmandineProjetBundle:Comment:create.html.twig', array(
            'comment' => $comment,
            'form'    => $form->createView()
        ));
    }


    protected function getProduct($id)
    {
        $em = $this->getDoctrine()
                    ->getEntityManager();

        $product = $em->getRepository('AmandineProjetBundle:Product')->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        return $product;

    }

}