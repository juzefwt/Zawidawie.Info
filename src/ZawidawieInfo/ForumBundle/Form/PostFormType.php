<?php

/**
 * This file is part of the FOSCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace ZawidawieInfo\ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;

class PostFormType extends AbstractType
{
    /**
     * Configures a Comment form.
     *
     * @param FormBuilder $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden');
        $builder->add('message', 'textarea');
/*
        if (!$this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $builder->add('authorName', 'text');
        }*/
    }

	public function getDefaultOptions(array $options)
	{
		return array(
			'data_class' => 'ZawidawieInfo\ForumBundle\Entity\ForumPost',
		);
	}

    public function getName()
    {
        return 'Post';
    }
}
