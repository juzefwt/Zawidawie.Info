<?php
namespace ZawidawieInfo\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class NewsAdmin extends Admin
{
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'created_at' // field name
    );

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('user');
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
            ->add('title')
            ->add('content', null, array('required' => false, 'attr' => array('class' => 'ckeditor')))
            ->add('user')
            ->end()
            ->with('Options')
            ->add('is_published')
            ->add('is_short', null, array('required' => false))
            ->add('expires_at')
            ->end();
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('created_at')
            ->add('user')
            ->add('is_published')
            ->add('is_short');
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('user')
            ->add('title');
    }
}