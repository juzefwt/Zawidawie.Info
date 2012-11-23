<?php

namespace ZawidawieInfo\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StaticController extends Controller
{
    public function indexAction()
    {
        return $this->render('ZawidawieInfoCoreBundle:Default:index.html.twig');
    }

    public function showAction($slug)
    {
        return $this->render('ZawidawieInfoCoreBundle:Static:show.html.twig', array('slug' => $slug));
    }
}
