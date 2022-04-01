<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user=$options["data"]; // je recupere la variable User qui est lié au formulaire dans le controlleur, dans la methode createForm()
        $builder
            ->add('pseudo', TextType::class, [
                "constraints" => [
                    New Assert\NotNull(['message'=>"merci de renseigner un pseudo"]),
                    new Assert\Length([
                        'min'=> 4,
                        'minMessage'=>"le pseudo doit contenir au moins 4 caracteres"
                    ])
                ]   
            ])
            ->add('email', EmailType::class, [
                'mapped'=> false,
                'label'=>'E-mail',
                'constraints'=> [
                new Assert\NotNull (['message'=> 'le Email ne peut pas etre vide'])
                ]
            ])
           
            ->add('roles', ChoiceType::class, [
                'choices'=> [
                    'Administrateur'    => 'ROLE_ADMIN',
                    'joueur'            => 'ROLE_PLAYER',
                    'arbitre'           => 'ROLE_REFEREE',
                    'utilisateur'       => 'ROLE_USER'
                ],
                'multiple' => true,
                'expanded' => true // case à cocher

            ])
            ->add('password', TextType::class, [
                'mapped' => false,
                'required'=>$user->getId() ? false:true//si l'id n'est pas nul, le champ password n'est ^pas requis (edit) sinon il l'est (new)
                // on aurait pu ecrire 
                // 'required'=>!$user->getId()
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'required'  => false
        ]);
    }
}
