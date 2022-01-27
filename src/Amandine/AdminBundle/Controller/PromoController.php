<?php

namespace Amandine\AdminBundle\Controller;

use Amandine\UserBundle\Entity\User;
use Amandine\ProjetBundle\Entity\Promo;
use Amandine\ProjetBundle\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use FOS\UserBundle\Model\UserInterface;

class PromoController extends Controller
{
	public function promoAction(Request $request)
	{
		$listPromos = $this->getDoctrine()
			->getRepository('AmandineProjetBundle:Promo')
			->findAll()
		;

		return $this->render('AmandineAdminBundle:Promo:promo.html.twig', array(
			'listPromos' => $listPromos
		));
	}

	public function addAction(Request $request)
	{
		$promo = new Promo();

		$form = $this->get('form.factory')->createBuilder(FormType::class, $promo)
			->add('type', ChoiceType::class, array(
				'choices' => array(
					'euro' => 'euro',
					'%' => '%')
				))
			->add('startingDate', DateTimeType::class, array(
				'years' => range(date('Y'), date('Y')+5),
				'data' => new \DateTime()
				))
			->add('deadline', DateTimeType::class, array(
				'years' => range(date('Y'), date('Y')+5),
				'data' => new \DateTime()
				))
			->add('code', TextType::class)
			->add('value', IntegerType::class)
			->add('save', SubmitType::class)
			->getForm()
		;

		if($request->isMethod('POST'))
		{
			$form->handleRequest($request);

			if($form->isSubmitted() && $form->isValid())
			{
				$em = $this->getDoctrine()->getManager();
				$em->persist($promo);
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice', 'Votre promotion a été ajouté');

				return $this->redirectToRoute('amandine_admin_viewpromo', array(
					'id' => $promo->getId()));
			}
		}

		return $this->render('AmandineAdminBundle:Promo:add.html.twig', array(
			'form' => $form->createView(),
		));
	}

	public function viewAction($id)
	{
		/*recuperation des données en BDD de la table promo*/
		$em = $this->getDoctrine()->getManager();

		$promo = $em->getRepository('AmandineProjetBundle:Promo')
					->find($id)
		;

		if (null === $promo)
		{
			throw new Exception("la promotion de référence ".$id." n'existe pas.");
		}

		return $this->render('AmandineAdminBundle:Promo:view.html.twig', array(
			'promo' => $promo
		));
	}

	public function updateAction(Request $request, $id)
	{
		/*recuperation des données de la table promo*/
		$promo = $this->getDoctrine()
    		->getManager()
    		->getRepository('AmandineProjetBundle:Promo')
    		->find($id)
    	;

    	/* creation du formulaire */
    	$form = $this->get('form.factory')->createBuilder(FormType::class, $promo)
    		->add('type', TextType::class)
			->add('startingDate', DateType::class)
			->add('deadline', DateType::class)
			->add('code', TextType::class)
			->add('value', IntegerType::class)
			->add('save', SubmitType::class)
   
    		->getForm()
    	;

    	// $code = $promo->getCode();

    	/*enregistrement en BDD */
    	if ($request->isMethod('POST'))
    	{
    		$form->handleRequest($request);

    		if ($form->isSubmitted() && $form->isValid())
    		{
    			/*if(count($code) < 1)
    			{*/
	    			$em = $this->getDoctrine()->getManager();
					$em->persist($promo);
					$em->flush();

					$request->getSession()->getFlashBag()->add('notice', 'Votre promotion a été modifié.');

					return $this->redirectToRoute('amandine_admin_viewpromo', array('id' => $promo->getId()));
    				
    			/*}
    			else
    			{
    				return new Response("Le Code est invalide !");
    			}*/
    		}
    	}
		return $this->render('AmandineAdminBundle:Promo:add.html.twig', array(
			'form' => $form->createView(),
		));
	}

	public function deleteAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();

		// on recupere l'annonce $id
		$promo = $em->getRepository('AmandineProjetBundle:Promo')->findOneById($id);

		if ( !$promo)
		{
			throw $this->createNotFoundException("La promotion de référence " .$id." n'existe pas.");
		}

		$em->remove($promo);
		$em->flush();

		return $this->redirectToRoute('amandine_admin_promo');	
	}
}