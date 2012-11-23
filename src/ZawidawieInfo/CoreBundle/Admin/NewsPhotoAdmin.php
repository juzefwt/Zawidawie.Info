<?php
namespace ZawidawieInfo\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class NewsPhotoAdmin extends Admin
{

    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'DESC', // sort direction
//        '_sort_by' => 'created_at' // field name
    );

    public function configureShowFields(ShowMapper $showMapper)
    {
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('news')
                ->add('photo')
            ->end()
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('news')
            ->add('photo')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('news')
            ->add('photo')
        ;
    }
}