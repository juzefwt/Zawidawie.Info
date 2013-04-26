<?php
namespace ZawidawieInfo\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use ZawidawieInfo\CoreBundle\Entity\Article;

class ArticleAdmin extends Admin
{

    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'created_at' // field name
    );

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title')
            ->add('content')
            ->add('user')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Treść')
                ->add('title')
                ->add('content', 'ckeditor')
            ->end()
            ->with('Detale')
                ->add('is_published')
                ->add('item')
            ->end()
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
        ;
    }
}