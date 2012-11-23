<?php

/*
 * This file is part of the Doctrine Extensions Taggable package.
 * (c) 2011 Fabien Pennequin <fabien@pennequin.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZawidawieInfo\CoreBundle\Entity;

use FPN\TagBundle\Entity\TagManager as BaseTagManager;

/**
 * TagManager.
 *
 * @author Fabien Pennequin <fabien@pennequin.me>
 */
class TagManager extends BaseTagManager
{
    /**
     * Loads or creates multiples tags from a list of tag names
     *
     * @param array  $names   Array of tag names
     * @return Tag[]
     */
    public function loadOrCreateTags(array $names)
    {
        if (empty($names)) {
            return array();
        }
		
		foreach ($names as $key => $name)
		{
			$names[$key] = strtolower($name);
		}

        $names = array_unique($names);

        $builder = $this->em->createQueryBuilder();

        $tags = $builder
            ->select('t')
            ->from($this->tagClass, 't')

            ->where($builder->expr()->in('t.name', $names))

            ->getQuery()
            ->getResult()
        ;

        $loadedNames = array();
        foreach ($tags as $tag) {
            $loadedNames[] = strtolower($tag->getName());
        }

        $missingNames = array_diff($names, $loadedNames);
        if (sizeof($missingNames)) {
            foreach ($missingNames as $name) {
                $tag = $this->createTag($name);
                $this->em->persist($tag);

                $tags[] = $tag;
            }

            $this->em->flush();
        }

        return $tags;
    }
}
