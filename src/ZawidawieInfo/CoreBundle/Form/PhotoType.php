<?php

namespace ZawidawieInfo\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use ZawidawieInfo\CoreBundle\Entity\Photo;
use ZawidawieInfo\CoreBundle\Form\EventListener\AddFileFieldSubscriber;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('description', 'textarea')
            ->add('item', null, array(
                'required' => false
            ))
            ->add('tags_string', 'text');

        $subscriber = new AddFileFieldSubscriber($builder->getFormFactory());
        $builder->addEventSubscriber($subscriber);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ZawidawieInfo\CoreBundle\Entity\Photo',
        ));
    }

    public function getName()
    {
        return 'PhotoType';
    }
}