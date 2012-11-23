<?php

namespace ZawidawieInfo\ForumBundle\Twig;
use Herzult\Bundle\ForumBundle\Twig\ForumExtension as BaseForumExtension;
use Herzult\Bundle\ForumBundle\Model\Topic;

class ForumExtension extends BaseForumExtension
{
    public function urlForTopicReply(Topic $topic, $absolute = false)
    {
        return sprintf('%s?page=%d#reply', $this->urlForTopic($topic, $absolute), $this->topicNumPages($topic));
    }

    public function getName()
    {
        return 'forum';
    }

    /**
     * Returns a list of global functions to add to the existing list.
     *
     * @return array An array of global functions
     */
    public function getFunctions()
    {
        return array(
            'forum_urlForPost' => new \Twig_Function_Method($this, 'urlForPost', array(
                'is_safe' => array('html')
            )),
            'forum_urlForCategory' => new \Twig_Function_Method($this, 'urlForCategory', array(
                'is_safe' => array('html')
            )),
            'forum_urlForCategoryAtomFeed' => new \Twig_Function_Method($this, 'urlForCategoryAtomFeed', array(
                'is_safe' => array('html')
            )),
            'forum_urlForTopic' => new \Twig_Function_Method($this, 'urlForTopic', array(
                'is_safe' => array('html')
            )),
            'forum_urlForTopicAtomFeed' => new \Twig_Function_Method($this, 'urlForTopicAtomFeed', array(
                'is_safe' => array('html')
            )),
            'forum_urlForTopicReply' => new \Twig_Function_Method($this, 'urlForTopicReply', array(
                'is_safe' => array('html')
            )),
            'forum_topicNumPages' => new \Twig_Function_Method($this, 'topicNumPages', array(
                'is_safe' => array('html')
            )),
            'forum_autoLink' => new \Twig_Function_Method($this, 'autoLink', array(
                'is_safe' => array('html')
            )),
            'forum_timeInWords' => new \Twig_Function_Method($this, 'distanceOfTimeInWords', array(
                'is_safe' => array('html')
            )),
        );
    }

    function distanceOfTimeInWords($from_time, $to_time = null, $include_seconds = false)
    {
	$to_time = $to_time? $to_time: time();
        $suffix = ' temu';

	$distance_in_minutes = floor(abs($to_time - $from_time) / 60);
	$distance_in_seconds = floor(abs($to_time - $from_time));

	$string = '';
	$parameters = array();

	if ($distance_in_seconds > 0 && $distance_in_seconds <= 59)
	{
          $string = 'przed chwilą';
          $suffix = '';
	}
        else if ($distance_in_minutes == 1)
        {
          $string = 'minutę';
        }
        else if ($distance_in_minutes >= 2 && $distance_in_minutes <= 4)
        {
          $string = '%minutes% minuty';
          $parameters['%minutes%'] = $distance_in_minutes;
        }
	else if ($distance_in_minutes >= 5 && $distance_in_minutes <= 44)
	{
	  $string = '%minutes% minut';
	  $parameters['%minutes%'] = $distance_in_minutes;
	}
	else if ($distance_in_minutes >= 45 && $distance_in_minutes <= 89)
	{
	  $string = 'godzinę';
	}
	else if ($distance_in_minutes >= 90 && $distance_in_minutes <= 1439)
	{
	  $string = '%hours% godzin';
	  $parameters['%hours%'] = round($distance_in_minutes / 60);
	}
	else if ($distance_in_minutes >= 1440 && $distance_in_minutes <= 2879)
	{
	  $string = 'wczoraj';
          $suffix = '';
	}
	else if ($distance_in_minutes >= 2880 && $distance_in_minutes <= 43199)
	{
	  $string = '%days% dni';
	  $parameters['%days%'] = round($distance_in_minutes / 1440);
	}
	else if ($distance_in_minutes >= 43200 && $distance_in_minutes <= 86399)
	{
	  $string = 'miesiąc';
	}
        else if ($distance_in_minutes >= 2*43200 && $distance_in_minutes <= 5*43200-1)
        {
          $string = '%months% miesiące';
          $parameters['%months%'] = round($distance_in_minutes / 43200);
        }
	else if ($distance_in_minutes >= 5*43200 && $distance_in_minutes <= 525959)
	{
	  $string = '%months% miesięcy';
	  $parameters['%months%'] = round($distance_in_minutes / 43200);
	}
	else if ($distance_in_minutes >= 525960 && $distance_in_minutes <= 1051919)
	{
	  $string = 'rok';
	}
	else
	{
	  $string = 'ponad %years% lat';
	  $parameters['%years%'] = floor($distance_in_minutes / 525960);
	}
	  return strtr($string, $parameters).$suffix;
    }
}
