<?php
namespace Amandine\AdminBundle\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Amandine\UserBundle\Entity\User;
 
class UserAutocompleteController extends Controller
{
    public function indexAction()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AmandineUserBundle:User')
          ;
 
        $listUser = $repository->findBy(
            array(),                      // Critere
            array('id' => 'desc'),        // Tri
            null,                         // Limite
            null                          // Offset
          );
 
        
      return $this->render('AmandineAdminBundle:Autocompleteuser:index.html.twig', array(
          'listUser' => $listUser,
      ));
      
    }
}