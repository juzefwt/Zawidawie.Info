<?php

namespace ZawidawieInfo\WysiwygBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle as Bundle;

class ZawidawieInfoWysiwygBundle extends Bundle
{
    public function getParent()
    {
        return 'IHQSWysiwygBundle';
    }
}
