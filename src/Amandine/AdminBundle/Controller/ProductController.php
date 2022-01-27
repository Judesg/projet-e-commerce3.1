<?php

namespace Amandine\AdminBundle\Controller;

use Amandine\ProjetBundle\Entity\Product;
use Amandine\ProjetBundle\Entity\Sub_category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
	public function productAction(Request $request)
	{
		$listProducts = $this->getDoctrine()
			->getRepository('AmandineProjetBundle:Product')
			->findAll();
		
		return $this->render('AmandineAdminBundle:Product:product.html.twig', array(
				'listProducts' => $listProducts,
		));
	}

	public function viewAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$product = $em->getRepository('AmandineProjetBundle:Product')
					  ->find($id)
		;

		if (null === $product)
	    {
	      throw new NotFoundHttpException("Le produit d'id" .$id." n'existe pas .");
	      
	    }
	    

	    //  le render ne change pas , on passait avant un talbeau, maintenant un objet
	    return $this->render('AmandineAdminBundle:Product:view.html.twig', array(
	      'product' => $product
	    ));
	}


	public function addAction(Request $request)
	{
		// on crée un objet Product
		$product = new Product();

		// on crée le formBuilder grâce au service form factory
		$form = $this->get('form.factory')->createBuilder(FormType::class, $product)
			->add('name', TextType::class)
			->add('description', TextType::class)
			->add('maintenance', TextType::class)
			->add('material', TextType::class)
			->add('htPrice', NumberType::class)
			->add('color', TextType::class)
			->add('stock', IntegerType::class)
			->add('photo', FileType::class)
			->add('photoBis', FileType::class)
			->add('sub_category', EntityType::class, array(
			 'class' => Sub_category::class,
			 'choice_label' => 'name',))
			->add('save', SubmitType::class)
			->getForm()
		;


		// si la requete est en POST 
		if ($request->isMethod('POST'))
		{
			
			// on fait le lien Requete <-> Formulaire
			// a partir de maintenant , la variable $product contien les valeurs entrées dans le formulaire par l'admin
			$form->handleRequest($request);

			// on verifie que les valeur entrées sont corrects
			if ($form->isSubmitted() && $form->isValid())
			{
				// premier image
				$file = $product->getPhoto();
				
				$fileName = $this->generateUniqueFileName().".".$file->guessExtension();
				try
				{
					$file->move(
						$this->getParameter('photo_directory'),
						$fileName
					);
				}
				catch(FileException $e)
				{
					echo "<div class='msg'>Votre fichier n'a pas pue etre téléchargé</div>";
				}

				$product->setPhoto($fileName);


				// deuxieme image
				
				$fileBis = $product->getPhotoBis();
				$fileBisName = $this->generateUniqueFileName().".".$fileBis->guessExtension();
				try
				{
					$fileBis->move(
						$this->getParameter('photo_directory'),
						$fileBisName
					);
				}
				catch(FileException $e)
				{
					echo "<div class='msg'>Votre fichier n'a pas pue etre téléchargé</div>";
				}

				$product->setPhotoBis($fileBisName);

				$em = $this->getDoctrine()->getManager();
				$em->persist($product);
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice', 'Votre produit a été enregistré.');

				// on redirige vers la page de visualisation des produits
				return $this->redirectToRoute('amandine_admin_viewproduct', array('id' => $product->getId()));
			}
		}

		// on passe la methode creatView() du formulaire a la vue afin qu'elle puisse afficher le formulaire toute seule
		return $this->render('AmandineAdminBundle:Product:add.html.twig', array(
			'form' => $form->createView(),
		));
	}

	private function generateUniqueFileName()
    {
        // md5() reduis les similitude entre les noms des fichier generé par
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

	public function updateAction(Request $request, $id)
	{
		// return $this->render('AmandineAdminBundle:Product:update.html.twig');
		$product = $this->getDoctrine()
			->getManager()
			->getRepository('AmandineProjetBundle:Product')
			->find($id)
		;

		$em = $this->getDoctrine()->getManager();

		$trucs = $product->setPhoto(new file($this->getParameter('photo_directory').'/'.$product->getPhoto()));
		$trucs2 = $product->setPhotoBis(new file($this->getParameter('photo_directory').'/'.$product->getPhotoBis()));
					
				

		$magin = $trucs->getPhoto();
		$magin2 = $trucs2->getPhotoBis();
			/*$product->setPhotoBis(
					new file($this->getParameter('photo_directory').'/'.$product->getPhotoBis())
				);*/
	// dump($magin->getFileName());exit;

		$form = $this->get('form.factory')->createBuilder(FormType::class, $product)
			->add('name', TextType::class)
			->add('description', TextType::class)
			->add('maintenance', TextType::class)
			->add('material', TextType::class)
			->add('htPrice', NumberType::class)
			->add('color', TextType::class)
			->add('stock', IntegerType::class)
			->add('photo', FileType::class, [ 'data' => $magin, 'required' => false])
			->add('photoBis', FileType::class, [ 'data' => $magin2, 'required' => false])
			->add('sub_category', EntityType::class, array(
			 'class' => Sub_category::class,
			 'choice_label' => 'name',))
			->add('save', SubmitType::class)
			->getForm()
		;

		

		$form->handleRequest($request);

		if ($request->isMethod('POST'))
		{
			$file = $form['photo']->getData();
			$fileBis = $form['photoBis']->getData();
			if ($form->isSubmitted() && $form->isValid())
			{
				

				if(!empty($file)) {
					$file = $product->getPhoto();
					$fileName = $this->generateUniqueFileName().".".$file->guessExtension();
					try
					{
						$file->move($this->getParameter('photo_directory'),$fileName);
					}
					catch(FileException $e)
					{
						echo "<div class='msg'>Votre fichier n'a pas pue etre téléchargé</div>";
					}

					$product->setPhoto($fileName);

				}else{
					$id = $product->getId();
		             $em = $this->getDoctrine()->getManager();
		             $product = $em->getRepository('AmandineProjetBundle:Product')->find($id);
		             // $fileName = $product->getPhoto();
		             $product->setPhoto($magin->getFileName());
				}

				if(!empty($fileBis))
				{
					$fileBis = $product->getPhotoBis();
					$fileBisName = $this->generateUniqueFileName().".".$fileBis->guessExtension();
					try
					{
						$fileBis->move($this->getParameter('photo_directory'),$fileBisName);
					}
					catch(FileException $e)
					{
						echo "<div class='msg'>Votre fichier n'a pas pue etre téléchargé</div>";
					}

					$product->setPhotoBis($fileBisName);
					
		            /*// Generate a unique name for the file before saving it
		            $fileName = md5(uniqid()).'.'.$file->guessExtension();

		            // Move the file to the directory where brochures are stored
		            $file->move( $this->getParameter('photo_directory'), $fileName );

		            $product->setPhoto($fileName);
		            dump($product->setPhoto($fileName));exit;

		            // Generate a unique name for the file before saving it
		            $fileNameBis = md5(uniqid()).'.'.$file->guessExtension();

		            // Move the file to the directory where brochures are stored
		            $fileBis->move( $this->getParameter('photo_directory'), $fileNameBis );

		            $product->setPhotoBis($fileNameBis);*/
		        }  else {

		             $id = $product->getId();
		             $em = $this->getDoctrine()->getManager();
		             $product = $em->getRepository('AmandineProjetBundle:Product')->find($id);
		             // $fileName = $product->getPhoto();
		            /* $product->setPhoto($magin->getFileName());*/
		             $product->setPhotoBis($magin2->getFileName());
		             // dump($product->setPhoto($magin), $product->setPhotoBis($magin2));exit;
		          }


				$em = $this->getDoctrine()->getManager();

        		$em->merge($product);
 
				// $em->persist($product);
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice', 'Votre produit a été modifié.');

				// on redirige vers la page de visualisation des produits
				return $this->redirectToRoute('amandine_admin_viewproduct', array('id' => $product->getId()));
			}
		}

		// on passe la methode creatView() du formulaire a la vue afin qu'elle puisse afficher le formulaire toute seule
		return $this->render('AmandineAdminBundle:Product:update.html.twig', array(
			'form' => $form->createView(),
			'product' => $product,
		));
	}

	public function deleteAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		// on recupere le produit $id
		$product = $em->getRepository('AmandineProjetBundle:Product')->find($id);

		$em->remove($product);
		$em->flush();

		return $this->redirectToRoute('amandine_admin_product');
	}

	public function stockAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();

		// on recupere le produit $id
		$product = $em->getRepository('AmandineProjetBundle:Product')->find($id);

		$trucs = $product->setPhoto(new file($this->getParameter('photo_directory').'/'.$product->getPhoto()));
		$trucs2 = $product->setPhotoBis(new file($this->getParameter('photo_directory').'/'.$product->getPhotoBis()));

		$magin = $trucs->getPhoto();
		$magin2 = $trucs2->getPhotoBis();

		$form = $this->get('form.factory')->createBuilder(FormType::class, $product)
			->add('stock', IntegerType::class, [
				'label' => 'Ajoutez du stock',
				'data' => 1,])
			->add('photo', FileType::class, [ 'data' => $magin, 'required' => false, 'attr' => ['style' => 'display:none;', 'class' => 'hidden-stock']])
			->add('photoBis', FileType::class, [ 'data' => $magin, 'label' => '', 'required' => false, 'attr' => ['style' => 'display:none;']])
			->add('save', SubmitType::class)
			->getForm()
		;

		// si la requete est en POST 
		if ($request->isMethod('POST'))
		{
			// on fait le lien Requete <-> Formulaire
			// a partir de maintenant , la variable $product contien les valeurs entrées dans le formulaire par le visiteur
			$form->handleRequest($request);

			$file = $form['photo']->getData();
			$fileBis = $form['photoBis']->getData();

				$stock = $product->getStock();
				// dump($stock);exit;
			// on verifie que les valeur entrées sont corrects
			if ($form->isSubmitted() && $form->isValid())
			{
				$id = $product->getId();
		        $em = $this->getDoctrine()->getManager();
		        $product = $em->getRepository('AmandineProjetBundle:Product')->find($id);
		        // $fileName = $product->getPhoto();
		        $product->setPhoto($magin->getFileName());
		        $product->setPhotoBis($magin2->getFileName());

				$product->setStock($stock);

				$em->persist($product);
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice', 'Votre stock a été modifié.');

				// on redirige vers la page de visualisation des produits
				return $this->redirectToRoute('amandine_admin_viewproduct', array('id' => $product->getId()));
			}
		}

		return $this->render('AmandineAdminBundle:Product:stock.html.twig', array(
			'form' => $form->createView(),
			'product' => $product,
		));
	}

}