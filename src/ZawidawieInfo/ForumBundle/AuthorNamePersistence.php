<?php

namespace ZawidawieInfo\ForumBundle;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Security\Core\SecurityContext;
use ZawidawieInfo\ForumBundle\Entity\ForumTopic;
use ZawidawieInfo\ForumBundle\Entity\ForumPost;
use DateTime;

class AuthorNamePersistence
{
    protected $securityContext;
    protected $request;
    protected $cookieName = 'lichess_authorName';

    public function __construct(SecurityContext $securityContext, Request $request)
    {
        $this->securityContext = $securityContext;
        $this->request = $request;
    }

    public function persistTopic(ForumTopic $topic, Response $response)
    {
        if($this->isAnonymous()) {
            $response->headers->setCookie(new Cookie(
                $this->cookieName,
                urlencode($topic->getLastPost()->getAuthorName()),
                time() + 15552000
            ));
        }
    }

    public function persistPost(ForumPost $post, Response $response)
    {
        if($this->isAnonymous()) {
            $response->headers->setCookie(new Cookie(
                $this->cookieName,
                urlencode($post->getAuthorName()),
                time() + 15552000
            ));
        }
    }

    public function loadPost(ForumPost $post)
    {
        if($this->isAnonymous()) {
            if ($authorName = $this->request->cookies->get($this->cookieName)) {
                $post->setAuthorName(urldecode($authorName));
            }
        }
    }

    protected function isAnonymous()
    {
        return !$this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED');
    }
}
