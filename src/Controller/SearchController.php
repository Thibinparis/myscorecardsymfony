<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(Request $request, GameRepository $gr): Response
    {
        $word=$request->query->get('serach');
        $jeux=$gr->findBySearch($word);
        /* SELECT * FROM game WHERE title LIKE '%test%' */
        return $this->render('search/index.html.twig', [
            'games' => $jeux,
            'mot'=>$word
        ]);
    }

    /* EXO afficher les resultats dans le fichier search/index.html.twig
    afficher aussi dans une balise h1: resultat de la recherch pour .... et replacer le .... par le mot tapé dans la barre de recherche */
}
