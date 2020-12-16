<?php

namespace App\Form;

use App\Entity\Anime;
use App\Repository\AnimeRepository;
use App\Repository\SerieRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AnimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('imageAnime', FileType::class, ['label' => 'Image (JPG, PNG)', 'data_class' => null, 'required' => false])
            ->add('description')
            ->add('dateDeSortieAt', DateTimeType::class, [
                'widget' => 'single_text'
            ])
            ->add('video', TextType::class,  ['label'=> 'Copier le code d\'intégration ' ] )
            // ->add('ajouter')
            //->add('categorie', ChoiceType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Anime::class

            /* 'attr' => [

                'data-autocomplete-url' => $this->router->generate('admin_utility_users')
            ] */
        ]);
    }


   /*  private $router;

    public function __construct(AnimeRepository $AnimeRepository, RouterInterface $router)
    {
        $this->router = $router;
    } */



    
}
