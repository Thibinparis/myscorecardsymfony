<?php

namespace App\Form;

use App\Entity\Player;
use App\Entity\Contest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class CommencerPartieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start_date', DateType::class, [
                'widget'=> 'single_text', // le champ sera affiché dans un input de type date
                'label' => "début de la partie",
                'constraints' => [ //chaque contrainte est un objet
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message'=> 'la partie ne peut pas se dérouler dans le passé!'
                    ])
                    ],
                    'help'=> 'la date doit etre postérieure ou égale à aujourd\'hui'

            ])
            ->add('players', EntityType::class, [ //EntitType parce que c'est lié à un objet, faut preciser quel objet de quelle class
                    'class'         => Player::class,
                    'choice_label'  => 'nickname',
                    'multiple'      => true,
                    'expanded'      => true,
                    'label'         => "Joueurs participants"


            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contest::class,
        ]);
    }
}
