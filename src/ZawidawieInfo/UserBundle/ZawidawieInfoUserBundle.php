<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZawidawieInfo\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use FOS\UserBundle\DependencyInjection\Compiler\SecurityEncoderFactoryPass;

/**
 * @author Matthieu Bontemps <matthieu@knplabs.com>
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class ZawidawieInfoUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
