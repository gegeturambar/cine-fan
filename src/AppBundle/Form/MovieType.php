<?php

namespace AppBundle\Form;

use AppBundle\Service\Subscriber\MovieFormSubscriber;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            /*  ->add('picture', FileType::class, [
                  'data_class' => null
              ])*/
            ->add('releaseDate', BirthdayType::class, [

            ])
            ->add('category', EntityType::class, [
                "class" => "AppBundle\Entity\Category",
                "choice_label" => 'name',
                "expanded"    => true,
                "multiple"      => false //checkbox
            ])
        ;

        //ajout d'un souscripteur
        $builder->addEventSubscriber(new MovieFormSubscriber());

    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Movie'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_movie';
    }


}
