<?php

namespace ZawidawieInfo\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use ZawidawieInfo\CoreBundle\Entity\CatalogItem;

class CatalogItemType extends AbstractType
{
    private $catalogItem;

    public function __construct(CatalogItem $item) {
        $this->catalogItem = $item;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('description', 'textarea')
            ->add('keywords', 'collection', array(
		'type' => new CatalogKeywordType(),
		'allow_add' => true,
		'allow_delete' => true,
		'by_reference' => false,
		'label' => 'Słowa kluczowe',
	     ))
            ->add('address')
            ->add('tel')
            ->add('mail')
            ->add('www')
            ->add('photo', 'file', array('required' => false));
    }

    public function getName()
    {
        return 'CatalogItemType';
    }
}