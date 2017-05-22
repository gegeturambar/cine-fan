<?php

namespace AppBundle\Form;

use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Translation\Translator;

class CommentaireType extends AbstractType
{

	private $authorizationChecker;

	private $translator;

	public function __construct(AuthorizationChecker $authorizationChecker, Translator $translator)
	{
		$this->authorizationChecker = $authorizationChecker;
		$this->translator = $translator;
	}

	/**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	//$this->authorizationChecker->isGranted()

        $builder
//            ->add('name')
            ->add('commentaire',\Symfony\Component\Form\Extension\Core\Type\TextType::class,array(
            	'translation_domain' => 'commentaire'
	        ))
        ;

        $builder->get('commentaire')
	        ->addModelTransformer(
	        	new CallbackTransformer(
	        		function ($slugAsName){
	        			//transform slug to name
				        return $this->translator->trans($slugAsName, array(), "commentaire");
			        },
			        function ($nameAsSlug){
				        return $nameAsSlug;
			        }
		        )
	        );
        if($this->authorizationChecker->isGranted('ROLE_ADMIN')){
        	$builder->add('published');
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Commentaire',
	        'translation_domain' => 'commentaire'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_commentaire';
    }


}
