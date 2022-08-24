<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessagesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('public/home.html.twig', [
            'controller_name' => 'Home page',
        ]);
    }

    #[Route('/whoami', name: 'whoami')]
    public function about(): Response
    {
        return $this->render('public/whoami.html.twig', [
            'controller_name' => 'Who am I?',
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, EntityManagerInterface $entityManager)
    {
        $messages = new Messages();
        
        $messageFormulaire = $this->createForm(MessagesType::class, $messages);
        // dd($contactFormulaire);
        $messageFormulaire->handleRequest($request);

        // dd($contactFormulaire);

        if ($messageFormulaire->isSubmitted() && $messageFormulaire->isValid()) {
            $entityManager->persist($messages);
            $entityManager->flush();
            return new Response('Ayé ENFIN!!!');
        }

        return $this->render('public/contact.html.twig', [
            'controller_name' => 'Formulaire de contact qui marche',
            'frmContact' => $messageFormulaire->createView()
        ]);


    }

    #[Route('/message', name: 'message')]
    public function message(Request $request, EntityManagerInterface $entityManager) {
        // La méthode findBy() permet d'affiner le résultat des requêtes
        // $resultat = $entityManager->getRepository(Messages::class)->findBy([
        //     'id' => [2, 3], // Récupérer les éléments qui ont l'id 2 et 3
        //     'nom' => 'DESC' // Les classer par nom DESC
        // ]);
        $resultat = $entityManager->getRepository(Messages::class)->findAll();

        return $this->render('public/message.html.twig', [
            'messages' => $resultat]);
    }
}