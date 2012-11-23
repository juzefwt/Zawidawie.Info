<?php

namespace ZawidawieInfo\ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;

class NewTopicFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
        $builder->add('subject');
        $builder->add('category');
        $builder->add('firstPost', 'lichess_forum.post');
    }

    public function getName()
    {
        return 'herzult_forum_post';
    }
}
