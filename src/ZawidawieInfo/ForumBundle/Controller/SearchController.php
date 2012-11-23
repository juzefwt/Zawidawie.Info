<?php

namespace ZawidawieInfo\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SearchController extends Controller
{
    public function searchAction()
    {
        $query = $this->get('request')->query->get('q', '');
        if ($query) {
            $results = $this->get('foq_elastica.finder.default.forum')->findPaginated($query);
            $results->setMaxPerPage(15);
            $results->setCurrentPage($this->get('request')->query->get('page', 1));

            return $this->render('ZawidawieInfoForumBundle:Search:results.html.twig', compact('query', 'results'));
        }

        return $this->render('ZawidawieInfoForumBundle:Search:search.html.twig');
    }
}
