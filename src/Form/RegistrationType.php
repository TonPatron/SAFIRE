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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\File\File;


class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('roles')
            ->add('nom')
            ->add('prenom')
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'homme' => 'homme',
                    'femme' => 'femme'
                ]

            ])
            ->add('birthday', BirthdayType::class, [
                'widget' => 'single_text'
         ])
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('confirmePassword', PasswordType::class)
            ->add('image', FileType::class, ['label' => 'Image (JPG, PNG)', 'data_class' => null, 'required' => false])
            ->add('user_responsability', CheckboxType::class, [
                'label'    => 'Cochez pour accepter la politique de confidentialité.',
                'required' => false,
            ]);
        //->add('inscriptionAt', DateTimeType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
