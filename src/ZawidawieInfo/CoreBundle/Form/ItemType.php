<?php

namespace ZawidawieInfo\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use ZawidawieInfo\CoreBundle\Entity\Item;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('type', 'choice', array('choices' => Item::getTypesArray()))
            ->add('parentItem');
    }

    public function getName()
    {
        return 'ItemType';
    }
}