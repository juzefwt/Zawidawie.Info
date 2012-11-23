<?php

namespace ZawidawieInfo\PagerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ZawidawieInfoPagerBundle extends Bundle
{
    public function getParent()
    {
        return 'WhiteOctoberPagerfantaBundle';
    }
}
