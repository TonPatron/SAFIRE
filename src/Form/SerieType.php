<?php

namespace App\Form;

use App\Entity\Serie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('imageSerie', FileType::class, ['label' => 'Image (JPG, PNG)','data_class' => null,'required' => false])
            ->add('description')
            ->add('dateDeSortieAt', DateTimeType::class, [
                'widget' => 'single_text'
         ])
            ->add('video', TextType::class,  ['label'=> 'Copier le code d\'intégration ' ] )

            //->add('ajouter')
            //->add('categorie', ChoiceType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
        ]);
    }
}
