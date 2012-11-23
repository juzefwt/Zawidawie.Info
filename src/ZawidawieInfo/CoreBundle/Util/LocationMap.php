<?php

namespace ZawidawieInfo\CoreBundle\Util;

use Vich\GeographicalBundle\Map\Map;

/**
 * LocationMap.
 */
class LocationMap extends Map
{
    /**
     * Constructs a new instance of LocationMap.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setContainerId('map');
        $this->setZoom(14);
        $this->setWidth(350);
        $this->setHeight(300);
    }
}