<?php

namespace Amandine\AdminBundle\Controller;

use Amandine\ProjetBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
	public function commentAction(Request $request)
	{
		$listComment = $this->getDoctrine()
			->getRepository('AmandineProjetBundle:Comment')
			->findAll()
		;


		return $this->render('AmandineAdminBundle:Comment:comment.html.twig', array(
			'listComment' => $listComment,
		));
	}

	public function deleteAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();

		// on recupere l'annonce $id
		$comment = $em->getRepository('AmandineProjetBundle:Comment')->findOneById($id);


		if ( !$comment)
		{
			throw $this->createNotFoundException("Le commentaire d'id " .$id." n'existe pas.");
			// return $this->redirectToRoute('amandine_admin_product');
		}


		$em->remove($comment);
		$em->flush();

		return $this->redirectToRoute('amandine_admin_comment');
		// return $this->redirectToRoute('amandine_admin_user');
		
		/*$this->objectManager->remove($user);
		$this->objectManager->flush();*/

		// return $this->redirect($this->generateUrl('amandine_admin_user'));
	}
}