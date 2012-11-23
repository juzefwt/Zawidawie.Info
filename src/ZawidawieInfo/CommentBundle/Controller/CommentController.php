<?php

namespace ZawidawieInfo\CommentBundle\Controller;

use FOS\CommentBundle\Controller\CommentController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\Form;

class CommentController extends BaseController
{
    /**
     * Forwards the action to the thread view on a successful form submission.
     *
     * @param CommentForm $form
     * @return Response
     */
    protected function onCreateSuccess(Form $form)
    {
        return new RedirectResponse($form->getData()->getThread()->getPermalink());
    }

    /**
     * Returns a 400 response when the form submission fails.
     *
     * @param CommentForm $form
     * @return Response
     */
    protected function onCreateError(Form $form)
    {
        return new Response('An error occurred with form submission', 400);
    }
}
