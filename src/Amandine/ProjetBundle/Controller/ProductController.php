<?php

namespace Amandine\ProjetBundle\Controller;

use Amandine\ProjetBundle\Entity\Product;
use Amandine\ProjetBundle\Entity\Sub_category;
use Amandine\ProjetBundle\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Amandine\ProjetBundle\Form\AjoutProduitType;



class ProductController extends Controller
{
    /* affichage d'un ensemble de produit via leur sous categorie*/
	public function subcategoryproduitAction(Request $request, $subCategory)
    {
    	$repository = $this->getDoctrine()
    					   ->getManager()
    					   ->getRepository('AmandineProjetBundle:Product');

    	$product = $repository
            ->findBy([ 'subCategory' => $subCategory]);
        ;

        $em=$this->getDoctrine()->getManager()->getRepository('AmandineProjetBundle:Sub_Category');

        $subCategoryId = $em->findOneBy(['id' => $subCategory]);

        $subCategoryName = $subCategoryId->getName();
        $htPrice = '';
        $htPrice = $request->request->get('htPrice');

        return $this->render('@AmandineProjet/Product/subcategoryproduit.html.twig', array(
        	/*'subCategory' => $subCategory,*/
            'product' => $product,
            'htPrice' => $htPrice,
            'subCategoryName' => $subCategoryName,
            
        ));
    }

    /* affichage d'un produit */
    public function produitAction(Request $request, $id)
    {
    	$em = $this->getDoctrine()->getManager();

		$product = $em->getRepository('AmandineProjetBundle:Product')
					  ->find($id)
		;

		if (null === $product)
	    {
	      throw new NotFoundHttpException("Le produit d'id" .$id." n'existe pas .");
	      
	    }
	    
        $comment = $em->getRepository('AmandineProjetBundle:Comment')
                   ->getCommentsForProduct($product->getId());

	    /* paginaion des commentaire*/
        $listcomment = $this->get('knp_paginator')->paginate(
            $comment,
            $request->query->get('page', 1),
            3
        );

	    return $this->render('@AmandineProjet/Product/produit.html.twig', array(
	      'product' => $product,
          'listcomment' => $listcomment
	    ));
    }

    /* selection de produit aleatoire pour les fiches produit*/
    public function productAleatoireAction(Request $request)
    {
       
        $em = $this->container->get('doctrine')->getEntityManager(); 
        $qb = $em->createQueryBuilder();
        $productAleatoire = $qb->select('p')
                               ->from('AmandineProjetBundle:Product', 'p')
                               ->orderBy('RAND()')
                               ->setMaxResults ('5')
                               ->getQuery()
                               ->getResult();

        return $this->render('AmandineProjetBundle:Product:productAleatoire.html.twig', array(
            'productAleatoire' => $productAleatoire,
        ));
    }

    /* affichage de la page nouveauté*/
    public function nouveauteAction()
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $qb = $em->createQueryBuilder();
        $nouveaute = $qb->select('p')
                        ->from('AmandineProjetBundle:Product', 'p')
                        ->where('p.addingDate >= :date')
                        ->setParameter('date', new \DateTime('-1 months'))
                        ->orderBy('RAND()')
                        ->getQuery()
                        ->getResult();

        return $this->render('AmandineProjetBundle:Product:nouveaute.html.twig', array(
            'nouveaute' => $nouveaute,
        ));

    }

    /* afficahe de la page des meilleures ventes*/
    public function meilleureVenteAction(Request $request)
    {
        /*on recupere les produits de chaque commmande */
        $em = $this->getDoctrine()->getManager();
        $allCommande = $em->getRepository('AmandineProjetBundle:Commande')->findAll();
        /* contien toute les commande */
        $trucs = $em->getRepository('AmandineProjetBundle:Commande')->findAll();

        foreach ($allCommande as $commandeId => $commande) 
        {
            $products = $commande->getProducts();

            foreach($products as $keys => $values)
            {
                /*plusieurs array contenant les id et quantité des produits*/
                 $totalProduct[] = $values;

                foreach($values as $product_id => $qty)
                {
                /*on lie l'ID des produit de commande au produit */
                    $tt_qty[] = $qty;
                    $tt_productId[] = $product_id;

                    $allProduct[] = $em->getRepository('AmandineProjetBundle:Product')->findBy(array('id' => $product_id));
                }
                 
            }       
        }

        // on relie la quentité avec les id des produits , en compilant les quantité avec les id en doublon
        foreach($tt_productId AS $key => $value) {
            if(isset($newarray[$value]))
            {
                $newarray[$value] += $tt_qty[$key];
            } 
            else 
            {
                $newarray[$value] = $tt_qty[$key]; 
            }
        }
        $end = 0;
        /*on trie le tableau par ordre decroissant*/
        arsort($newarray);

        /* on assigne les id au id produit et on limite l'affichage */
        foreach ($newarray as $key => $value) {
             $idproduct[] = $em->getRepository('AmandineProjetBundle:Product')->findBy(array('id' => $key));
             $end++;
             if($end == 2)
             {
                break;
             }
        }

        return $this->render('AmandineProjetBundle:Product:meilleureVente.html.twig', array(
            'allProduct' => $allProduct,
            'qty' => $qty,
            'newarray' => $newarray,
            'keys' => $keys,
            'idproduct' => $idproduct,
            // 'productz' => $productz
        ));
    }



}