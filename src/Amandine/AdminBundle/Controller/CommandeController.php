<?php

namespace Amandine\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Amandine\ProjetBundle\Entity\Product;

use Amandine\ProjetBundle\Entity\Commande;
use Amandine\ProjetBundle\Entity\Promo;
use Amandine\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class CommandeController extends Controller
{
	public function commandeAction(Request $request)
	{
		
        $listCommande = $this->getDoctrine()
                 ->getRepository('AmandineProjetBundle:Commande')
                 ->findAll();

        /*$date1 = new \DateTime('-24 hours');
        $date2 = new \DateTime('-8 days');*/
        // /dump($date1); exit;
        /*$attenteCommande = \DateTime::createFromFormat("Y-m-d H:i:s", $date1);
        $expeditionCommande = \DateTime::createFromFormat("Y-m-d H:i:s", $date2);*/
        $attenteCommande = new \DateTime('-24 hours');
        $expeditionCommande = new \DateTime('-8 days');

        return $this->render('AmandineAdminBundle:Commande:commande.html.twig', array(
        	'listCommande' => $listCommande,
                'attenteCommande' => $attenteCommande,
                'expeditionCommande' => $expeditionCommande,

        ));
	}

        public function viewAction($id)
        {
                $em = $this->getDoctrine()->getManager();

                $commande = $em->getRepository('AmandineProjetBundle:Commande')
                                          ->find($id)
                ;

                if (null === $commande)
            {
              throw new NotFoundHttpException("La commande d'id" .$id." n'existe pas .");
              
            }

            $attenteCommande = new \DateTime('-24 hours');
            $expeditionCommande = new \DateTime('-8 days');

            //  le render ne change pas , on passait avant un talbeau, maintenant un objet
            return $this->render('AmandineAdminBundle:Commande:view.html.twig', array(
              'commande' => $commande,
              'attenteCommande' => $attenteCommande,
                'expeditionCommande' => $expeditionCommande,
            ));
        }
}