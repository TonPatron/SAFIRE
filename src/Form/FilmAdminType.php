<?php

namespace App\Form;

use App\Entity\Film;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class FilmAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('imageFilm', FileType::class, ['label' => 'Image (JPG, PNG)','data_class' => null,'required' => false])
            ->add('description')
            ->add('dateDeSortieAt', DateTimeType::class, [
                'widget' => 'single_text'
         ])
            ->add('video')
            //->add('ajouter', ChoiceType::class)
            //->add('categorie', ChoiceType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}
