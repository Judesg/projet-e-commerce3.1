<?php

namespace Amandine\AdminBundle\Controller;
     
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Amandine\ProjetBundle\Entity\Product;
 
class AjaxAutoCompleteController extends Controller
{
    public function updateDataAction(Request $request)
    {
        $data = $request->get('input');
        
        $em = $this->getDoctrine()->getManager();
        $em2= $this->getDoctrine()->getManager();
 
        $query = $em->createQuery(''
                . 'SELECT c.id, c.name '
                . 'FROM AmandineProjetBundle:Product c ' 
                . 'WHERE c.name LIKE :data '
                . 'ORDER BY c.name ASC'
                )
                ->setParameter('data', '%' . $data . '%');
        $results = $query->getResult();
        $productList = '<ul id="matchList">';
        foreach ($results as $result) {
            $matchStringBold = preg_replace('/('.$data.')/i', '<strong>$1</strong>', $result['name']); // Replace text field input by bold one
            $productList .= '<li id="'.$result['name'].'"><a href="http://localhost/symfony/projet-e-commerce3/web/app_dev.php/admin/product/view/'.$result['id'] .'">'.$matchStringBold.'</a></li>'; // Create the matching list - we put maching name in the ID too
        }
        $productList .= '</ul>';
        $response = new JsonResponse();
        $response->setData(array('productList' => $productList));
        return $response;

    }
}