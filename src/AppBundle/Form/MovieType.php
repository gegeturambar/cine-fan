<?php

namespace AppBundle\Form;

use AppBundle\Service\Subscriber\MovieFormSubscriber;
use AppBundle\Service\Utils\TranslateService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class MovieType extends AbstractType
{
	private $authorizationChecker;

	private $translateService;

	public function __construct(AuthorizationChecker $authorizationChecker, TranslateService $translateService)
	{
		$this->authorizationChecker = $authorizationChecker;
		$this->translateService = $translateService;
	}

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
	        ->add('tags', EntityType::class, [
		        "class" => "AppBundle\Entity\Tag",
		        "choice_label" => 'slug',
		        "expanded"    => true,
		        "multiple"      => true, //checkbox,
		        'choice_translation_domain' => 'tag'
	        ])
        ;


	    if($this->authorizationChecker->isGranted('ROLE_ADMIN')){
		    $builder->add('published');
		    $builder->add('price', NumberType::class);

		    $builder->get('price')
			    ->addModelTransformer(
				    new CallbackTransformer(
					    function ($priceLocale){
						    //transform price from locale to current
						    return $this->translateService->translatePriceFromLocaleToCurrent($priceLocale);
					    },
					    function ($priceCurrent){
						    return $this->translateService->translatePriceFromCurrentToLocale($priceCurrent);
					    }
				    )
			    );
	    }

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
