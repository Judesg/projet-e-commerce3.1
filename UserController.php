<?php

namespace Amandine\AdminBundle\Controller;

use Amandine\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use FOS\UserBundle\Model\UserInterface;

class UserController extends Controller
{
    public function userAction(Request $request)
    {
        $listUsers = $this->getDoctrine()
        	->getRepository('AmandineUserBundle:User')
        	->findAll()
        ;

        return $this->render('AmandineAdminBundle:User:user.html.twig', array(
        	'listUsers' => $listUsers,
        ));
    }

    public function viewAction($id)
    {
    	$em = $this->getDoctrine()->getManager();

    	$user = $em->getRepository('AmandineUserBundle:User')
    			   ->find($id)
    	;

    	if (null === $user)
    	{
    		throw new Exception("l'utilisateur d'id".$id."n'existe pas.");
    		
    	}

    	return $this->render('AmandineAdminBundle:User:view.html.twig', array(
    		'user' => $user,
    	));

    }

    public function addAction(Request $request)
    {
    	$user = new User();

    	$form = $this->get('form.factory')->createBuilder(FormType::class, $user)
    		->add('username', TextType::class)
    		->add('userLastname', TextType::class)
    		->add('userFirstname', TextType::class)
    		->add('email', TextType::class)
    		->add('address', TextType::class)
    		->add('pc', TextType::class)
    		->add('city', TextType::class)
    		->add('gender', TextType::class)
    		->add('birthDate', DateType::class, array(
                    'widget' => 'choice',
                    'years' => range(date('Y')-18, date('Y')-100)
                    ))
    		->add('password', PasswordType::class)
    		->add('phone', NumberType::class)
    		->add('save', SubmitType::class)
   
    		->getForm()
    	;

    	if($request->isMethod('POST'))
    	{
    		$form->handleRequest($request);

    		if($form->isSubmitted() && $form->isValid())
    		{
    			$em = $this->getDoctrine()->getManager();
    			$em->persist($user);
    			$em->flush();

    			$request->getSession()->getFlashBag()->add('notice', 'Votre utilisateur a été ajouté.');

    			return $this->redirectToRoute('amandine_admin_viewuser', array('id' => $user->getId()));
    		}
    	}

    	return $this->render('AmandineAdminBundle:User:add.html.twig', array(
    		'form' => $form->createView(),
    	));
    }

    public function updateAction(Request $request, $id)
    {
    	$user = $this->getDoctrine()
    		->getManager()
    		->getRepository('AmandineUserBundle:User')
    		->find($id)
    	;

    	$form = $this->get('form.factory')->createBuilder(FormType::class, $user)
    		->add('username', TextType::class)
    		->add('userLastname', TextType::class)
    		->add('userFirstname', TextType::class)
    		->add('email', TextType::class)
    		->add('address', TextType::class)
    		->add('pc', TextType::class)
    		->add('city', TextType::class)
    		->add('gender', TextType::class)
    		->add('birthDate', DateType::class)
    		/*->add('password', PasswordType::class)*/
    		->add('phone', NumberType::class)
    		->add('save', SubmitType::class)
   
    		->getForm()
    	;

    	if ($request->isMethod('POST'))
    	{
    		$form->handleRequest($request);

    		if ($form->isSubmitted() && $form->isValid())
    		{
    			$em = $this->getDoctrine()->getManager();
				$em->persist($user);
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice', 'Votre utilisateur a été modifié.');

				return $this->redirectToRoute('amandine_admin_viewuser', array('id' => $user->getId()));
    		}
    	}

    	return $this->render('AmandineAdminBundle:User:add.html.twig', array(
    		'form' => $form->createView(),
    	));
    }

   
    public function deleteAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();

		// on recupere l'annonce $id
		$user = $em->getRepository('AmandineUserBundle:User')->findOneById($id);


		if ( !$user)
		{
			throw $this->createNotFoundException("L'utilisateur d'id " .$id." n'existe pas.");
			// return $this->redirectToRoute('amandine_admin_product');
		}


		$em->remove($user);
		$em->flush();

		return $this->redirectToRoute('amandine_admin_user');
		// return $this->redirectToRoute('amandine_admin_user');
		
		/*$this->objectManager->remove($user);
		$this->objectManager->flush();*/

		// return $this->redirect($this->generateUrl('amandine_admin_user'));
	}
}
