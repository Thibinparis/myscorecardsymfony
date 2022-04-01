<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GameRepository;
use App\Entity\Game;
use App\Form\GameType;
use Doctrine\ORM\EntityManagerInterface;


class GameController extends AbstractController
{
    #[Route('/admin/game', name: 'app_admin_game')]
    public function index(GameRepository $gameRepository): Response
    {
        // on ne peut pas instancier d'objet d'une classe Repository on doit les passer sans les arguments d'une methode d'un controller 
        // NB: pour chaque classe Entity crée, il y a une classe Repository qui correspond et qui permet de faire des requetes SELECT sur la table correspondante
        // $gameRepository = new GameRepository;
        return $this->render('admin/game/index.html.twig', ['games'=> $gameRepository->findAll()]);
    }

    #[Route('/admin/game/new', name: 'app_admin_game_new')]

    // * La classe Request permet d'instancier un objet qui contient 
    // * toutes les valeurs des variables super-globales de PHP.
    // * Ces valeurs sont dans des propriétés (qui sont des objets).
    // *  $request->query      contient        $_GET
    // *  $request->request    contient        $_POST
    // *  $request->server     contient        $SERVER
    // * ...
    // *  Pour accéder aux valeurs, on utilisera sur ces propriétés la 
    // *  méthode ->get('indice') 
    // la classe EntityManager va permettre d'ececuter les requete qui modifient les données dans la bdd (Insert, Update, delete)
    // l'EntityManager va toujours utiliser des objets Entity pour modifier les données

    public function new(Request $request, EntityManagerInterface $em){
        // dd($request->request->get('game[title]'));

        $jeu = new Game;
        // on cree un objet $form pour gerer le formulare, il est crée à partir de la classe GameType. On relie ce formulaire à l'objet $jeu
        $form = $this->createForm(GameType::class, $jeu);

        // l'objet $form va gerer ce qui vient de la requete HTTP c'est à dire avec l'objet $request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // la methode persist prepare la requete INSEERT avec les donnees de l'objet passé en argument 
            $em->persist($jeu);
            // le methode flush execute les requetes en attente et donc modifie la bdd
            $em->flush();

            // redirection vers une route du projet. on mets ici le nom de la route et pas le URL
            Return $this->redirectToRoute("app_admin_game");

        }
        return $this->render("admin/game/form.html.twig", ["formGame"=>$form->CreateView()]) ;
    }
// on mets dans la URL de la route un variable qui va servir dans la methode excutée qui est liée avec ce URL
    #[Route('/admin/game/edit{id}', name: 'app_admin_game_edit')]
    public function edit(Request $rq, EntityManagerInterface $em, GameRepository $gameRepository, $id) {
        $jeu = $gameRepository-> find($id);
        $form=$this ->createForm(GameType::class, $jeu);
        $form->handleRequest($rq);
        if($form ->isSubmitted() &&  $form->isValid()) {
            $em->flush();
            Return $this->redirectToRoute("app_admin_game");
        }

        return $this -> render("admin/game/form.html.twig", ['formGame'=> $form ->createView()]);

    }
     /**
     * @Route("/admin/game/modifier/{title}", name="app_admin_game_modifier")
     * 
     * Si le chemin de la route contient une partie variable (donc entre {}), on peut récupérer une objet entité
     * directement avec la valeur de cette partie de l'URL. Il faut que le nom de ce paramètre soit le nom d'une
     * propriété de la classe Entity.
     * Par exemple, le paramètre est {title}, parce que dans l'entité Game il y a une propriété title.
     * Dans les arguments de la méthode, on peut alors utiliser un objet de la classe Game ($jeu dans l'exemple)
     */
    public function modifier(Request $rq, EntityManagerInterface $em, Game $jeu)
    {
        // $jeu = $gameRepository->find($id);
        $form = $this->createForm(GameType::class, $jeu);
        $form->handleRequest($rq);
        if(  $form->isSubmitted() && $form->isValid() ){
            $em->flush();
            return $this->redirectToRoute("app_admin_game");
        }

        return $this->render("admin/game/form.html.twig", [ "formGame" => $form->createView() ]);
    }

 /**
     * EXO 
     *  1 .créer une route app_admin_game_delete, qui prend l'id comme paramètre
     *  2. afficher les informations du jeu à supprimer avec une nouvelle vue 
     *         Confirmation de suppression du jeu suivant :
     *              . titre
     *              . Entre nb_min et nb_max joueurs
     *          
     */

    #[Route('/admin/game/delete{id}', name: 'app_admin_game_delete')]
    public function delete(Request $rq, EntityManagerInterface $em, GameRepository $gr, $id) {
        $jeu = $gr-> find($id);
        if( $rq-> isMethod("POST")) {
            $em->remove($jeu);
            $em->flush();
            return $this->redirectToRoute ('app_admin_game');

        }
        return $this -> render("admin/game/delete.html.twig", ['game'=> $jeu]);

    }


}
