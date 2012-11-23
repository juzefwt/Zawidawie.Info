<?php

namespace ZawidawieInfo\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use ZawidawieInfo\CoreBundle\Entity\News;
use ZawidawieInfo\CoreBundle\Form\EventListener\UpdateTagsSubscriber;
use Symfony\Component\DependencyInjection\Container;

class NewsType extends AbstractType
{
    private $container;

    public function setContainer(Container $container)
    {
	$this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('title', 'text')
          ->add('content', 'textarea')
          ->add('tags_string', 'text')
          ->add('relatedPhotos', 'entity', array(
            'class' => 'ZawidawieInfoCoreBundle:Photo',
            'property'     => 'title',
            'multiple'     => true,
          ));
    }

    public function getName()
    {
        return 'NewsType';
    }
}