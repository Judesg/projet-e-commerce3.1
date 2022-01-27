<?php

namespace Amandine\ProjetBundle\Controller;

use Amandine\ProjetBundle\Entity\Product;
use Amandine\ProjetBundle\Entity\Sub_category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Amandine\ProjetBundle\Entity\Entity;
use Amandine\ProjetBundle\Repository\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Amandine\ProjetBundle\Form\FilterType;

class PagesController extends Controller
{
    public function accueilAction()
    {
        return $this->render('@AmandineProjet/Pages/accueil.html.twig');
    }

    public function allproduitAction(Request $request)
    {
        $listProducts = $this->getDoctrine()
            ->getRepository('AmandineProjetBundle:Product')
            ->findAll()
        ;

        $product = $this->get('knp_paginator')->paginate(
            $listProducts,
            $request->query->get('page', 1),
            30
        );


        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);

    	return $this->render('@AmandineProjet/Pages/produit.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    public function livraisonAction()
    {
    	return $this->render('@AmandineProjet/Pages/livraison.html.twig');
    }

    public function retourAction()
    {
        return $this->render('@AmandineProjet/Pages/retour.html.twig');
    }

    public function paiementligneAction()
    {
        return $this->render('@AmandineProjet/Pages/paiementligne.html.twig');
    }

    public function cgvAction()
    {
        return $this->render('@AmandineProjet/Pages/cgv.html.twig');
    }

    public function mentionAction()
    {
    	return $this->render('@AmandineProjet/Pages/mention.html.twig');
    }

    public function contactAction(Request $request)
    {
        // Create the form according to the FormType created previously.
        // And give the proper parameters
        $form = $this->createForm('Amandine\ProjetBundle\Form\ContactType',null,array(
            // To set the action use $this->generateUrl('route_identifier')
            'action' => $this->generateUrl('amandine_projet_contact'),
            'method' => 'POST'
        ));

        if ($request->isMethod('POST')) {
            // Refill the fields in case the form is not valid.
            $form->handleRequest($request);

            if($form->isValid()){
                // Send mail
                if($this->sendEmail($form->getData())){

                    // Everything OK, redirect to wherever you want ! :
                    
                    return $this->redirectToRoute('amandine_projet_accueil');
                }else{
                    // An error ocurred, handle
                    var_dump("Errooooor :(");
                }
            }
        }
        return $this->render('@AmandineProjet/Pages/contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

    private function sendEmail($data){
        $myappContactMail = 'noreply.ledodoparisien@gmail.com';
        $myappContactPassword = 'Symfonyback123!';
        
        // In this case we'll use the ZOHO mail services.
        // If your service is another, then read the following article to know which smpt code to use and which port
        // http://ourcodeworld.com/articles/read/14/swiftmailer-send-mails-from-php-easily-and-effortlessly
        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465,'ssl')
            ->setUsername($myappContactMail)
            ->setPassword($myappContactPassword);

        $mailer = \Swift_Mailer::newInstance($transport);
        
        $message = \Swift_Message::newInstance("Message de contact ". $data["subject"])
        ->setFrom(array($myappContactMail => "Message de ".$data["name"]))
        ->setTo(array(
            $myappContactMail => $myappContactMail
        ))
        ->setBody("' ".$data["message"]." '"."<br><br>ContactMail : ".$data["email"], 'text/html');
        
        return $mailer->send($message);
    }

    public function rechercherAction(Request $request)
    {

        if($request->isXmlHttpRequest())
        {
            $name = '';
            $name = $request->request->get('name');

            $em = $this->container->get('doctrine')->getEntityManager();

            if($name != '')
            {
                $qb = $em->createQueryBuilder();

                $qb->select('a')
                   ->from('AmandineProjetBundle:Product', 'a')
                   ->where('a.name LIKE :name')
                   ->orderBy('a.name', 'ASC')
                   ->setParameter('name', '%'.$name.'%');

                $query = $qb->getQuery();
                $product = $query->getResult();
            }
            else {
                $product = $em->getRepository('AmandineProjetBundle:Product')->findAll();
            }

            return $this->render('AmandineProjetBundle:Pages:produit.html.twig', array(
                'product' => $product,
            ));
        }
        else {
            return $this->allproduitAction();
        }
    }
}
