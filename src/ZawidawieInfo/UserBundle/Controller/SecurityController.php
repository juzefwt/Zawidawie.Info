<?php

namespace ZawidawieInfo\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\SecurityContext;
use FOS\UserBundle\Controller\SecurityController as BaseController;



class SecurityController extends BaseController
{
    public function loginAction()
    {
        if (true === $this->container->get('security.context')->isGranted('ROLE_USER')) {
            $url = $this->container->get('router')->generate('homepage');
            return new RedirectResponse($url);
        }

        $request = $this->container->get('request');
        /* @var $request \Symfony\Component\HttpFoundation\Request */
        $session = $request->getSession();
        /* @var $session \Symfony\Component\HttpFoundation\Session */

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Security:login.html.'.$this->container->getParameter('fos_user.template.engine'), array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
}
