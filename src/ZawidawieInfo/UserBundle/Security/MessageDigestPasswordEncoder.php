<?php
namespace ZawidawieInfo\UserBundle\Security;

use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder as BaseEncoder;

class MessageDigestPasswordEncoder extends BaseEncoder {
    // overwrite
    protected function mergePasswordAndSalt($password, $salt) {
        return $salt . $password;
    }
}