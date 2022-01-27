<?php
namespace Amandine\AdminBundle\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Amandine\ProjetBundle\Entity\Product;
 
class AutocompleteController extends Controller
{
    public function indexAction()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AmandineProjetBundle:Product')
          ;
 
        $listProduct = $repository->findBy(
            array(),                      // Critere
            array('id' => 'desc'),        // Tri
            null,                         // Limite
            null                          // Offset
          );
 
        
      return $this->render('AmandineAdminBundle:Autocomplete:index.html.twig', array(
          'listProduct' => $listProduct,
      ));
      
    }
}