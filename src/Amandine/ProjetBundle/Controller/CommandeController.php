<?php

namespace Amandine\ProjetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Amandine\ProjetBundle\Entity\Product;

use Amandine\ProjetBundle\Entity\Commande;
use Amandine\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\DateTime;

class CommandeController extends Controller
{
	/* affichage de la commande et enregistrement en BDD */
	public function CommandeAction(Request $request)
	{
		/* affichage des donnéeS du panier validé*/
		$session = $request->getSession();
		$em = $this->getDoctrine()->getManager();

		/*recuperation de la session basket*/
		$basket = $session->get('basket', array());
	       

        $products=array();
	    foreach(($session->get('basket')) as $key => $prod)
	    {
	          $products[]=$em->getRepository('AmandineProjetBundle:Product')->find($key);
	          
	    }
	    
	    $commande = new Commande();
	    /* recuperation de l'id utilisateur */
	    $commande->setUser($this->getUser());
	    /* recuperation des données de la session basket pour les rentrer dans la table commande*/
	    $commande->setProducts($basket);

	    /* insertion en BDD des informationde la commande*/
		$form = $this->get('form.factory')->createBuilder(FormType::class, $commande)
			->add('htPrice', HiddenType::class)
			->add('ttcPrice', HiddenType::class)
			->add('ttTva', HiddenType::class)
			/*->add('products', CollectionType::class, array(
						'data' => $basket,
						'attr' => array(
							'style' => 'display:none;')))*/
			/*->add('promo', TextType::class, array(
						'label' => 'Code promo',
						'required'   => false,))*/			
			->add('livraison', ChoiceType::class, array(
						'label' => 'Mode de livraison :',
						'choices' => array(
									'A domicile' => 'domicile',
									'En relai-colis' => 'colis',
									'En magasin' => 'magasin')))

			->add('paiement', ChoiceType::class, array(
						'label' => 'Mode de paiement :',
						'choices' => array(
									'amex' => 'American Express',
									'applePay' => 'Appel Pay',
									'mastercard' => 'MasterCard',
									'paypal' => 'Paypal',
									'visa' => 'Visa')))
								
			->add('save', SubmitType::class, array(
						'label' => 'Payer'))
			->getForm();

			/* si le post est valide , on rentre les données en BDD */
		if ($request->isMethod('POST'))
		{
			$form->handleRequest($request);
			if($form->isSubmitted() && $form->isValid())
			{
				

				$em = $this->getDoctrine()->getManager();
				$em->persist($commande);
				$em->flush();

				$session->set('commande', $form->getData());


				// mise a jour du stock 
				foreach(($session->get('basket')) as $key => $qty)
			    {
			        $product=$em->getRepository('AmandineProjetBundle:Product')->find($key);
			        $oldStock = $product->getStock();
			        $product->setStock($oldStock - $qty);
			        $em->persist($product);
			        $em->flush();
			    }


				$session->getFlashBag()->add('notice', 'Votre commande a été enregistré.');
				$session->clear('basket');

        		return $this->redirectToRoute('amandine_projet_promo', array(
        			'id' => $commande->getId(),

        		));
			}
		}

		return $this->render('AmandineProjetBundle:Commande:commande.html.twig', array(
            'products' => $products,
            'form' => $form->createView(),
            'basket' => $session->get('basket'),
        ));
	}

	/* affichage de la page apres validation de la commande */
	public function viewCommandeAction(Request $request, $id)
	{

	    $em = $this->getDoctrine()->getManager();
		$commande = $em->getRepository('AmandineProjetBundle:Commande')->find($id);
		$products = $commande->getProducts();
		
	

	/* assigner un produit a l'id retourné dans products*/
	    foreach($products as $productId )
	    {
	    	foreach ($productId as $key => $values) {
		      	$allProduct[] = $em->getRepository('AmandineProjetBundle:Product')->findBy(array('id' => $key));
	    	}
	    }


		return $this->render('AmandineProjetBundle:Commande:viewCommande.html.twig', array(
			'id' => $id,
			'commande' => $commande,
			'products' => $products,
			'allProduct' => $allProduct,

		));
	}

	/* affiche les 5dernieres commandes expediées de l'utilisateur */
	public function afficherCommandeAction()
	{
		$user = $this->getUser();

        $em = $this->container->get('doctrine')->getEntityManager(); 
		$qb = $em->createQueryBuilder();
        $listCommande = $qb->select('e')
                 ->from('AmandineProjetBundle:Commande', 'e')
                 ->where('e.validatingDate <= :date AND e.user = :user')
                 ->setParameter('date', new \DateTime('-8 days'))
                 ->setParameter('user', $user)
                 ->orderBy('e.validatingDate', 'DESC')
                 ->setMaxResults(5)
                 ->getQuery()
                 ->getResult();

        return $this->render('AmandineProjetBundle:Commande:afficherCommande.html.twig', array(
        	'listCommande' => $listCommande
        ));
	}

	/*Affiche les commandes en cour d'expedition (entre 24h et 7jours)*/
	public function expeditionCommandeAction(Request $request)
	{
        $user = $this->getUser();
        $em = $this->container->get('doctrine')->getEntityManager(); 
		$qb = $em->createQueryBuilder();
        $expeditionCommande = $qb->select('e')
                 ->from('AmandineProjetBundle:Commande', 'e')
                 ->where('e.validatingDate BETWEEN :date AND :dateIn AND e.user = :user')
                 ->setParameter('date', new \DateTime('-7 days'))
                 ->setParameter('dateIn', new \DateTime('-25 hours'))
                 ->setParameter('user', $user)
                 ->orderBy('e.validatingDate', 'DESC')
                 ->getQuery()
                 ->getResult();


        return $this->render('AmandineProjetBundle:Commande:expeditionCommande.html.twig', array(
        	'expeditionCommande' => $expeditionCommande,
        	
        ));
	}

	/* affichage des commandes en cour de preparation ( de moins de 24h)*/
	public function attenteCommandeAction(Request $request)
	{
		$user = $this->getUser();
        $em = $this->container->get('doctrine')->getEntityManager(); 
		$qb = $em->createQueryBuilder();
        $attenteCommande = $qb->select('c')
                 ->from('AmandineProjetBundle:Commande', 'c')
                 ->where('c.validatingDate >= :date AND c.user = :user')
                 ->setParameter('date', new \DateTime('-24 hours'))
                 ->setParameter('user', $user)
                 ->orderBy('c.validatingDate', 'DESC')
                 ->getQuery()
                 ->getResult();

        return $this->render('AmandineProjetBundle:Commande:attenteCommande.html.twig', array(
        	'attenteCommande' => $attenteCommande,
        ));
	}
}