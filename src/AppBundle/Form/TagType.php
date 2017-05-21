<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class TagType extends AbstractType
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
    	//$this->authorizationChecker->isGranted()

        $builder
            ->add('name')
//            ->add('slug')
        ;
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
            'data_class' => 'AppBundle\Entity\Tag'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_tag';
    }


}
