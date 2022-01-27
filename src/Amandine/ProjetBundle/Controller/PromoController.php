<?php

namespace Amandine\ProjetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Amandine\ProjetBundle\Entity\Promo;
use Amandine\ProjetBundle\Entity\Commande;
use Amandine\ProjetBundle\Entity\Liaison_basket;
use Amandine\UserBundle\Entity\User;
use Amandine\ProjetBundle\Form\AjoutProduitType;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PromoController extends Controller
{
	public function PromoAction(Request $request, $id)
	{
		/*recuperation de la commande*/
		$commande = $this->getDoctrine()->getManager()->getRepository('AmandineProjetBundle:Commande')->find($id);

		//*formulaire pour le code promo*/ 
		$form = $this->get('form.factory')->createBuilder(FormType::class, $commande)
			->add('promo', TextType::class, array(
                        'label' => 'Code promo',
                        'required'   => false,
    			))
			->add('save', SubmitType::class)
			->getForm()
		;


		/*validation du formpulaire*/
		if($request->isMethod('POST')) {
			
			$form->handleRequest($request);

			/* le code saisi par l'utilisateur */
			$promo = $commande->getPromo();

			/*on recherche si le code promo taper existe en BDD*/
			$promoC = $this->getDoctrine()->getManager()->getRepository('AmandineProjetBundle:Promo')->findOneBy(['code' => $promo ]);

			if($form->isValid() && $form->isSubmitted())
			{
				if($promo !== null)
				{	
					
					if( $promoC !== NULL && $promo < 1)
					{
						$promoStart = $promoC->getStartingDate();
						$promoEnd = $promoC->getDeadline();
						$date = date("Y-m-d H:i:s");
						$today = \DateTime::createFromFormat("Y-m-d H:i:s", $date);

						 // dump($promoStart, $today, $promoEnd); exit;
						/*verification si le code est valide au niveau des date*/
						if(($promoStart == $today || $promoStart < $today) && ($promoEnd == $today || $promoEnd > $today))
						{
							/*on recupere le prix ttc de la commande*/
							$ttcPrice = $commande->getTtcPrice();

							/* on calcule le nouveau prix selon si la promo est en € ou en % */
							$typePromo = $promoC->getType();

							if($typePromo == 'euro')
							{
								$newTtcPrice = $ttcPrice-$promoC->getValue();
							} else {
								$newTtcPrice = $ttcPrice-$ttcPrice*$promoC->getValue()/100;
							}

							$idPromo = $promoC;

							/* on rentre les nouvelles données dans la table commande*/
							$commande->setPromo($idPromo);
							$commande->setTtcPricePromo($newTtcPrice);

							/* mise a jour des donnée de la BDD */
							$em = $this->getDoctrine()->getManager();
							$em->persist($commande);
							$em->flush(); 

							/* on ajoute un message flash*/
							$this->addFlash(
				            'notice',
				            'Votre code promo a été validé, <br> Montant de votre commande mis a jour : '.$newTtcPrice.'€'
				            );
							return $this->redirectToRoute('amandine_projet_view_commande', array(
			        			'id' => $id,
			        			'commande' => $commande,
			        			'promoC' => $promoC,
			        			'promo' => $promo
			        		));
						}else
						{
							$this->addFlash(
				            'invalide',
				            'Votre code promo est erroné'
				            );
				            return $this->redirectToRoute('amandine_projet_promo', array(
				            	'form' => $form->createView(),
					            'id' => $id,
					            'commande' => $commande,
					            'promoC' => $promoC));
						}

					}else 
					{
						$this->addFlash(
			            'invalide',
			            'Votre code promo est invalide'
			            );
			            return $this->redirectToRoute('amandine_projet_promo', array(
			            	'form' => $form->createView(),
				            'id' => $id,
				            'commande' => $commande,
				            'promoC' => $promoC,
				        ));
					}
				}
			}


		}
		return $this->render('AmandineProjetBundle:Promo:promo.html.twig', array(
			'form' => $form->createView(),
            'id' => $id,
            'commande' => $commande,
 		));
	}
}