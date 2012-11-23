<?php

namespace ZawidawieInfo\ForumBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ZawidawieInfoForumBundle extends Bundle
{
    public function getParent()
    {
        return 'HerzultForumBundle';
    }
}
