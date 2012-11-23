<?php

namespace ZawidawieInfo\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use ZawidawieInfo\CoreBundle\Entity\ItemAttachment;

class ItemAttachmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	  ->add('item')
	  ->add('name')
          ->add('file')
          ->add('url');
    }

    public function getName()
    {
	return 'ItemAttachmentType';
    }
}