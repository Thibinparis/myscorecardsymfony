<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Contest ;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use App\Form\CommencerPartieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(GameRepository $gr, PlayerRepository $pr): Response
    {
        return $this->render('home/index.html.twig', [
            'games'=> $gr->findAll(),
            'winners'=>$pr->findWinner()
        ]);
    }

    #[Route("/commencer-une-partie-{title}", name: "app_home_contest")]
    public function commencer (Game $jeu, EntityManagerInterface $em, Request $rq) {

        $partie = new Contest;
        $partie->setGame($jeu); //la propriété game de contest est un objet, donc on insert un objet
        $form = $this->createForm(CommencerPartieType::class, $partie) ; // ici je relie l'objet $partie au formulaire
        $form -> handleRequest($rq) ; // permet à savoir si le formulaire est soumis ou pas
        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($partie);
            $em->flush();
            $this->addFlash("success", "la nouvelle partie a été enregistrée"); //le premier parametre est un type de message, on utilise ici une classe de bootstrap success
            // $this->addFlash("danger", "Message d'erreur");
            return $this->redirectToRoute("app_home");
        }
        return $this->render("home/commencer.html.twig", [
            "form"  =>$form->createView()
        ]);
    } 


}
