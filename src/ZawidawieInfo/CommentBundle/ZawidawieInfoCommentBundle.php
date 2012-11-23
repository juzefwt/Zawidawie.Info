<?php

/*
 * This file is part of the FOSCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZawidawieInfo\CommentBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Matthieu Bontemps <matthieu@knplabs.com>
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class ZawidawieInfoCommentBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSCommentBundle';
    }
}
