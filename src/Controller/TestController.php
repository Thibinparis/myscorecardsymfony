<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{

    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            "texte"=>"comment ça va?",
        ]);
    }

    #[Route('/test/calcul')]
    public function calcul(): Response
    {
        $a=12;
        $b=7;
        return $this->render('test/index.html.twig', [
            'calcul' => $a +$b,
             "texte"=>"comment ça va?",
            'conroller_name'=>"Didier"
        ]);
    }


    #[Route('/test/salut')]
    public function salut(): Response
    {
        return $this->render('test/salut.html.twig', ['prenom'=>'Thibaut']);
    }

    #[Route('/test/tableau')]
    public function tableau(): Response
    {
         $tableau = ["bonjour", "je m'appelle", 789, true,12,38];
         return $this ->render ("test/tableau.html.twig",["tableau"=>$tableau]);
    }

    #[Route('/test/tableau-assoc')]
    public function tab(): Response
    {
         $p = ["nom"=> "cérien",
                "prenom" => "jean",
                "age"=>32
        ];
         return $this ->render ("test/assoc.html.twig",["personne"=>$p]);
    }

    #[Route('/test/objet')]
    public function objet(): Response
    {
         $objet = new \stdclass ;
         $objet-> prenom="Nordine";
         $objet-> nom="Ateur";
         $objet-> age=40;

         return $this ->render ("test/assoc.html.twig",["personne"=>$objet]);
    }




}
