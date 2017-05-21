<?php

namespace AppBundle\Form;

use AppBundle\Service\Subscriber\ActorFormSubscriber;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class ActorType extends AbstractType
{
	private $authorizationChecker;

	public function __construct(AuthorizationChecker $authorizationChecker)
	{
		$this->authorizationChecker = $authorizationChecker;
	}

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom')
            ->add('nom')
            ->add('dateNaissance', BirthdayType::class, [

            ])
            ->add('movies', EntityType::class, [    /*ce champ ne se trouve pas dans la table actor mais est la liaison avec les movies dans la table de liaison. Ca a été défini dans l'entité*/
                "class" => "AppBundle\Entity\Movie",
                "placeholder" => 'labelcategorylist',
                "choice_label" => 'title',
                "expanded"    => true,
                "multiple"      => true //checkbox
            ])
	        /*
        ->add('categories', EntityType::class, [
                "class" => "AppBundle\Entity\Category",
                "placeholder" => 'labelcategorylist',
                "choice_label" => 'name',
                "expanded"    => true,
                "multiple"      => true //checkbox
            ])
	        */
        ;

	    if($this->authorizationChecker->isGranted('ROLE_ADMIN')){
		    $builder->add('published');
	    }

        //ajout d'un souscripteur
        $builder->addEventSubscriber(new ActorFormSubscriber());

    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Actor'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_actor';
    }


}
