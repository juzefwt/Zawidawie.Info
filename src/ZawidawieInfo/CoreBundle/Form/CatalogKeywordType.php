<?php

namespace ZawidawieInfo\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CatalogKeywordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array('label' => 'Usługa'));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'ZawidawieInfo\CoreBundle\Entity\CatalogKeyword',
        );
    }

    public function getName()
    {
        return 'catalog_keyword';
    }
}