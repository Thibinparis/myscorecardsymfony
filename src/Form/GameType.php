<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label'             => "nom du jeu",
                'constraints'       => [
                    new Length([
                        'max'   => 30,
                        'max>Message'=> "le nom du jeu ne peut pas dépasser 30 caractères",
                        'min' => 3,
                        'minMessage'=> "le nom du jeu doit avoir au moins 3 caractères" 
                    ]),
                    new Notblank([
                        'message' =>'ce champ ne peut pas etre vide'
                    ])
                ]
            ])
            ->add('min_players')
            ->add('max_players')
            ->add('enregistrer', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
