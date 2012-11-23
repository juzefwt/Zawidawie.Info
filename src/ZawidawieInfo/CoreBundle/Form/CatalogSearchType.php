<?php

namespace ZawidawieInfo\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CatalogSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query', 'text', array('label' => 'Nazwa firmy lub usługa:'));
    }

    public function getName()
    {
        return 'CatalogSearchType';
    }
}