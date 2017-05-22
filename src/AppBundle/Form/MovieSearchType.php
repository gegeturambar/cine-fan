<?php

namespace AppBundle\Form;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieSearchType extends AbstractType
{

    protected $doctrine;

    public function __construct(Registry $doctrine){
        $this->doctrine = $doctrine;//pour avoir accès à Doctrine lors du buildform, on a mis ce MovieSearchType dans service.yml : c'est donc devenu un service à qui on passe en parametre dans service.yml doctrine... on peut donc faire des requêtes pour construire le formulaire :)
    }

    public function getMoviesYears(){
        $rc = $this->doctrine->getRepository('AppBundle:Movie');/*select*/
        $results = $rc->getYears();
        return $results;
    }

    /**
     * {@inheritdoc}
     *
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $years = $this->getMoviesYears();
        $valDate =[];
        foreach($years as $year){
            $valDate[$year['releaseDate']->format('Y')] = $year['releaseDate']->format('Y');
        }

        $builder
            ->add('title')
            ->add('releaseDate', ChoiceType::class, [
                'choices' =>
                    $valDate,

                'placeholder' => ''

          ])
            ->add('category', EntityType::class, [
                "class" => "AppBundle\Entity\Category",
                "placeholder" => 'labelcategorylist',
                "choice_label" => 'name',
                "expanded"    => true,
                "multiple"      => false //checkbox,

            ])
	        ->add('tags', EntityType::class, [
		        "class" => "AppBundle\Entity\Tag",
		        "choice_label" => 'slug',
		        "expanded"    => true,
		        "multiple"      => true, //checkbox,
		        'choice_translation_domain' => 'tag'
	        ])
        ;


    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        //on l'attache à une class qui mapp ce formulaire de recherche pour repopulate le formulaire une fois soumis
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Form\Model\SearchTypeModel'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_movie_search';
    }


}
