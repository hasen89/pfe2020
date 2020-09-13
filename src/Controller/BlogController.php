<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ArticleRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController {

    /**
     * @Route("/",name="home")
     */
    public function home(ServiceRepository $serviceRepository) {

        return $this->render('blog/home.html.twig', [
                    'services' => $serviceRepository->findBy([], ['id' => 'DESC'], 5)]);
    }

    /**
     * @Route("/contact",name="contact")
     */
    public function conatct(Request $request, EntityManagerInterface $manager, \Swift_Mailer $mailer) {
        $form = $this->createForm(ContactType::class);
        $contact = new Contact;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $message = (new \Swift_Message('Nouveau contact'))
                    // on attribue l'expediteur
                    ->setFrom('emailab@hotmail.fr')
                    //on aatribue le destinateur
                    ->setTo('dtstataouinel@gmail.com')
                    // on cree le message avec le vue twig
                    ->setBody(
                    $this->renderView('Emails/email.html.twig', compact('contact'),
                            'text/html')
                    )
            ;
            // on envoi le message
            $mailer->send($message);
            $manager->persist($contact);
            $manager->flush();
            $this->addFlash(
                    'notice',
                    'votre message etait envoyé!');

            return $this->redirectToRoute('home');
        }

        return $this->render('blog/contact.html.twig', [
                    'form' => $form->createView()]);
    }

    /**
     * @Route("/about",name="about")
     */
    public function about() {
        return $this->render('product/about.html.twig');
    }

}
