<?php

namespace ZawidawieInfo\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


class CatalogKeywordReference
{
//     /**
//      * @ORM\Id
//      * @ORM\Column(type="integer")
//      * @ORM\generatedValue(strategy="AUTO")
//      */
//     protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="CatalogItem", inversedBy="keywords")
     */
    protected $catalog_item;

    /**
     * @ORM\ManyToOne(targetEntity="Keyword")
     */
    protected $keyword;
}