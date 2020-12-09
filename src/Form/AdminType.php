<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'administrateur' => 'ROLE_ADMIN',
                    'utilisateur' => 'ROLE_USER',
                ],
                'multiple' => true,
                'required' => true,
            ])
            ->add('nom')
            ->add('prenom')
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'homme' => 'homme',
                    'femme' => 'femme'
                ]

            ])
            ->add('birthday', BirthdayType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('confirmePassword', PasswordType::class)
            ->add('image', FileType::class, ['label' => 'Image (JPG, PNG)']);
        // ->add('inscriptionAt');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
