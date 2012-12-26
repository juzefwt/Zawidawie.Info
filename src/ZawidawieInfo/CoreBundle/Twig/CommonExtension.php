<?php

namespace ZawidawieInfo\CoreBundle\Twig;

use ZawidawieInfo\CoreBundle\Util\BBCode\BbCode;

/**
 *
 * @author Wojciech Treter <juzefwt@gmail.com>
 * @package ZawidawieInfo
 * @subpackage Twig
 */
class CommonExtension extends \Twig_Extension
{
    /**
     * Returns a list of filters.
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            'html_entity_decode'    => new \Twig_Filter_Method($this, 'entityDecode', array('is_safe' => array('html'))),
            'bbcode'    => new \Twig_Filter_Method($this, 'parseBbcode', array('is_safe' => array('html'))),
            'strip_bbcode'    => new \Twig_Filter_Method($this, 'stripBbcode', array('is_safe' => array('html'))),
        );
    }

    /**
     * Name of this extension
     *
     * @return string
     */
    public function getName()
    {
        return 'Common';
    }

    public function entityDecode($value)
    {
	return htmlspecialchars_decode($value);
    }

    public function parseBbcode($value)
    {
        $bbcode = new BbCode();
        $bbcode->getSettings()->trustText = true;
        return $bbcode->parse($value);
    }

    public function stripBbcode($value)
    {
	$stripped = strip_tags($this->parseBbcode($value));
        return empty($stripped)
	    ? '[...]'
	    : $stripped;
    }
}


