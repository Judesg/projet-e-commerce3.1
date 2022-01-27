<?php

namespace Amandine\ProjetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Amandine\ProjetBundle\Entity\Product;
use Amandine\ProjetBundle\Entity\Basket;
use Amandine\ProjetBundle\Entity\Liaison_basket;
use Amandine\UserBundle\Entity\User;
use Amandine\ProjetBundle\Form\AjoutProduitType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class LiaisonBasketController extends Controller
{
    /* afficage du panier d'achat */
    public function viewBasketAction(Request $request)
    {
        $user = $this->getUser();
        //récupère la session
        $session = $request->getSession();
        // Si la session du panier n'existe pas ,elle est créée
        if (!$session->has('basket')) $session->set('basket', array());
        $em = $this->getDoctrine()->getManager();

        $products=array();
	    foreach(array_keys($session->get('basket')) as $prod){
	           $products[]=$em->getRepository('AmandineProjetBundle:Product')->find($prod);
	    }

        return $this->render('AmandineProjetBundle:Basket:basket.html.twig', array(
            'products' => $products,
            'user' => $user,
            'basket' => $session->get('basket'),
        ));
    }

    /* ajoute un article au panier */
    public function addBasketAction($id, Request $request)
    {
        // Récupère la session
        $session = $request->getSession();
        // Vérifie si la session panier existe déjà
        if (!$session->has('basket'))
        {
            $session->set('basket', array());
            $this->addFlash('notice', " Article ajouté avec succès");
        } 
            
        $basket = $session->get('basket');

        $basket[$id] = 1;

        // Vérfie si l'id du produit existe déjà dans notre panier
        if (array_key_exists($id, $basket)) {
            if ($request->query->get('stock') != null)
                // Affectation nouvelle quantité
                $basket[$id] = $request->query->get('stock');
                
        }else {
            if ($request->query->get('stock') != null)
                // Ajout nouvelle quantité
                $basket[$id] = $request->query->get('stock');
            else
                // Quantité par défaut
                $basket[$id] = 1;
        }
        $session->set('basket', $basket);
        return $this->redirectToRoute('amandine_projet_produit', array('id' =>$id));
    }

    /* supprimer un article du panier */
    public function removeBasketAction($id, Request $request)
    {
        // récupère la session
        $session = $request->getSession();
        $basket = $session->get('basket');
        // Vérifier si l'id produit est bien dans le panier
        if (array_key_exists($id, $basket))
        {
            // Supprime le produit de panier
            unset($basket[$id]);
            // Mise à jour de la session
            $session->set('basket', $basket);
            $session->getFlashBag()->add('success', " Article supprimé avec succès ");
        }
        return $this->redirect($this->generateUrl('amandine_projet_basket'));
    }

    /* suppression de l'integralité du panier*/
    public function lostBasketAction(Request $request/*, \Swift_Mailer $mailer*/)
    {
        $user = $this->getUser();
      /*  $email = $user->getEmail();
        $name = $user->getUserName();*/

        /*recuperation de la session*/
    	$session = $request->getSession();
        $session->set('basket', array());

        /* effacement de la session basket et du panier*/
        $session->clear('basket');
       
        /*$message = (new \Swift_Message('Hello'))
            ->setForm('noreply.ledodoparisien@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'Emails/relance.html.twig',
                    array('name' => $name)
                ),
                'text/html'
            )
        ;

        $mailer->send($message);*/

        return $this->redirect($this->generateUrl('amandine_projet_basket'/*, array(
            'name' => $name
        )*/));
    }

    
}