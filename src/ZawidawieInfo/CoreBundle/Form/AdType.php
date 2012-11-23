<?php

namespace ZawidawieInfo\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use ZawidawieInfo\CoreBundle\Entity\Ad;

class AdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	  ->add('title', 'text')
	  ->add('content', 'textarea');
    }

    public function getName()
    {
	return 'AdType';
    }
}