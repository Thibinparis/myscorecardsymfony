<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Player;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as Hasher;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, Hasher $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!in_array("ROLE_ADMIN", $user->getRoles())){
                //cette fonction test si un string exist dans un array, le premier argument est le string et le second est l'array
                $newPlayer = new Player;
                $newPlayer->setNickname( $user->getPseudo());
                $newPlayer->setEmail($form->get('email')->getData());
                // on peut aussi recuperer le Email via l'objet Request->request->get('Email')
                $user->setPlayer($newPlayer);  //ca va créer un player et lier le user et le player    
            }
            else {
                $user->setRoles(['ROLE_ADMIN']);
                //si l'utilisateur a coché le role 'admin' - il aura que le role admin et tous les autres roles cochés dans le formulaire seront supprimés
            }
            $mdp = $form->get('password')->getData();
            $password=$hasher->hashPassword($user, $mdp);
            $userRepository->add($user);
            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!in_array("ROLE_ADMIN", $user->getRoles())){
                //cette fonction test si un string exist dans un array, le premier argument est le string et le second est l'array
                $newPlayer = new Player;
                $newPlayer->setNickname( $user->getPseudo());
                $newPlayer->setEmail($form->get('email')->getData());
                // on peut aussi recuperer le Email via l'objet Request->request->get('Email')
                $user->setPlayer($newPlayer);  //ca va créer un player et lier le user et le player    
            }
            else {
                $user->setRoles(['ROLE_ADMIN']);
                //si l'utilisateur a coché le role 'admin' - il aura que le role admin et tous les autres roles cochés dans le formulaire seront supprimés
            }

            if ($mdp=$form->get('password')->getData()) {
                $password=$hasher->hashPassword($user, $mdp);
                $user->setPassword($password);
            }
            $userRepository->add($user);
            //set nouvelle method de la class Repository de symfony fait deja l'enregistrement elle fait persiste et flush. Elle replace à utiliser UserEntityInterface
            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }

        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
    // #[Route('/update', name:'app_admin_user_update')
    public function update(UserRepository $ur, EntityManagerInterface $em) {

            $listedUser=$ur->findAll();
            $compteur=0;
            foreach($listedUser as $utilisateur) {
                if (!in_array("ROLE_ADMIN", $utilisateur->getRoles()) && !$utilisateur->getPlayer()){
                        $nouveauJoueur=new Player;
                        $nouveauJoueur->SetNickname($utilisateur->getPseudo())
                                        -> setEmail($utilisateur->getPseudo().'@yopmail.com');
                        $utilisateur->setPlayer($nouveauJoueur);
                        $compteur++;
                }
            }
            $em->flush();
            $this->addFlash('success', 'Mise à jour des utilisateurs réussie !');
            return $this->redirectToRoute("app_admin_player_index");
    }
}
