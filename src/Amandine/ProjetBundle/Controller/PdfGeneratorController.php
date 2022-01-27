<?php

namespace Amandine\ProjetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Amandine\ProjetBundle\Entity\Commande;
use Amandine\UserBundle\Entity\User;


class PdfGeneratorController extends Controller
{
	public function factureAction(Request $request, $id)
	{
		$user = $this->getUser();
		$repository = $this->getDoctrine()
    					   ->getManager()
    					   ->getRepository('AmandineProjetBundle:Commande');

    	$commande = $repository
            ->find($id);
        ;

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

		$html = $this->renderView('AmandineProjetBundle:Commande:facture.html.twig', array(
			'commande' => $commande,
			'user' => $user,
			'encoding' => 'utf-8',
			'id' => $id,
			'products' => $products,
			'allProduct' => $allProduct,) 
		);

        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            'facture-ledodoparisien.pdf'
        );
	}
}