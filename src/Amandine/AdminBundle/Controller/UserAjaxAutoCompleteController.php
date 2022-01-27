<?php

namespace Amandine\AdminBundle\Controller;
     
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Amandine\UserBundle\Entity\User;
 
class UserAjaxAutoCompleteController extends Controller
{
    public function updateDataAction(Request $request)
    {
        $data = $request->get('input');
        
        $em = $this->getDoctrine()->getManager();
        $em2= $this->getDoctrine()->getManager();
 
        $query = $em->createQuery(''
                . 'SELECT c.id, c.username '
                . 'FROM AmandineUserBundle:User c ' 
                . 'WHERE c.username LIKE :data '
                . 'ORDER BY c.username ASC'
                )
                ->setParameter('data', '%' . $data . '%');
        $results = $query->getResult();
        $userList = '<ul id="matchList">';
        foreach ($results as $result) {
            $matchStringBold = preg_replace('/('.$data.')/i', '<strong>$1</strong>', $result['username']); // Replace text field input by bold one
            $userList .= '<li id="'.$result['username'].'"><a href="http://localhost/symfony/projet-e-commerce3/web/app_dev.php/admin/user/view/'.$result['id'] .'">'.$matchStringBold.'</a></li>'; // Create the matching list - we put maching name in the ID too
        }
        $userList .= '</ul>';
        $response = new JsonResponse();
        $response->setData(array('userList' => $userList));
        return $response;

    }
}