<?php

namespace Amandine\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AmandineAdminBundle:Default:index.html.twig');
    }
}
