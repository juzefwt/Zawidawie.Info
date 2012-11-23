<?php
namespace ZawidawieInfo\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use ZawidawieInfo\CoreBundle\Entity\Item;

class ItemAdmin extends Admin
{

    protected $datagridValues = array(
        '_page'       => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'created_at' // field name
    );

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('user')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('name')
                ->add('description')
                ->add('type', 'choice', array('choices' => Item::getTypesArray()))
                ->add('parentItem')
            ->end()
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
        ;
    }
}