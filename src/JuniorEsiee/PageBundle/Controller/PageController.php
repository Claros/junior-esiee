<?php

namespace JuniorEsiee\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JuniorEsiee\PageBundle\Form\OffreFormType;
use JuniorEsiee\PageBundle\Form\ContactFormType;

class PageController extends Controller
{
    function contactAction()
    {
        $form = $this->createForm(new  ContactFormType);
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->handleRequest($request);

			if ($form->isValid()) {
				// Perform some action, such as sending an email
				$data = $form -> getData();
				
				 $message = \Swift_Message::newInstance()
				->setSubject('Message de contact depuis de le site web')
				->setFrom($data['common']['email'])
				->setTo('walleta@esiee.fr')
				->setBody($this->renderView('JuniorEsieePageBundle:Page:EmailContact.html.twig', array('data' => $data)));
				
				
				$response = $this->get('mailer')->send($message);

				$this->get('session')->getFlashBag()->add('success', '<strong>Votre message a bien été envoyé à nos équipes, merci de votre intérêt.</strong>');
				
				// Redirect - This is important to prevent users re-posting
				// the form if they refresh the page
				return $this->redirect($this->generateUrl('page_accueil'));
			}

		}
		return $this->render('JuniorEsieePageBundle:Page:contact.html.twig', array('form' => $form->createView(),)); 
	}	
		
	
		
		function indexAction()
		{
			return $this->render('JuniorEsieePageBundle:Page:index.html.twig');
		}
		
		function aboutAction()
		{
			return $this->render('JuniorEsieePageBundle:Page:about.html.twig');
		}
		
		function prestationsAction()
		{
			return $this->render('JuniorEsieePageBundle:Page:prestations.html.twig');
		}
		
		function appeloffreAction()
		{
			$form = $this->createForm(new OffreFormType);
			
			$request = $this->getRequest();
			if ($request->getMethod() == 'POST') {
				$form->handleRequest($request);

				if ($form->isValid()) {
					// Perform some action, such as sending an email
					$data = $form -> getData();
					
					if ( $form['cahiercharges']->getData() != null) {
						$file1 = $form['cahiercharges']->getData();
						
						$file1->move('tmp', 'cahier_charges.pdf');
					}
					
					if ( $form['chartegraph']->getData() != null) {
						$file2 = $form['chartegraph']->getData();
						$file2->move('tmp', 'charte_graphique.pdf');
					}
					
					 $message = \Swift_Message::newInstance()
					->setSubject("Message d'appel d'offre depuis de le site web")
					->setFrom($data['common']['email'])
					->setTo('walleta@esiee.fr');
					
					if ( $form['cahiercharges']->getData() != null) {
						$message->attach(\Swift_Attachment::fromPath('tmp/cahier_charges.pdf'));
					}
					
					if ( $form['chartegraph']->getData() != null) {
						$message->attach(\Swift_Attachment::fromPath('tmp/charte_graphique.pdf'));
					}
					
					$message->setBody($this->renderView('JuniorEsieePageBundle:Page:EmailOffre.html.twig', array('data' => $data)));

					$response = $this->get('mailer')->send($message);
						
					
					$this->get('session')->getFlashBag()->add('success', '<strong>Votre message a bien été envoyé à nos équipes</strong>, merci de votre intérêt.');
					
					// Redirect - This is important to prevent users re-posting
					// the form if they refresh the page
					return $this->redirect($this->generateUrl('page_accueil'));
				}

			}

			return $this->render('JuniorEsieePageBundle:Page:appeloffre.html.twig', array('form' => $form->createView(),));
		}
}