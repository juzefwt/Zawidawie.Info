<?php
namespace ZawidawieInfo\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CatalogItemAdmin extends Admin
{

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'created_at' // field name
    );

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name');
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('description', null, array('required' => false, 'attr' => array('class' => 'ckeditor')))
            ->add('user')
            ->add('category', 'entity', array('class' => 'ZawidawieInfo\CoreBundle\Entity\CatalogCategory',
            'query_builder' => function (\Doctrine\ORM\EntityRepository $repository)
            {
                return $repository->createQueryBuilder('s')->add('orderBy', 's.name ASC');
            }))
            ->add('address')
            ->add('tel')
            ->add('mail')
            ->add('www')
            ->add('photo', 'file', array('required' => false));
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('address')
            ->add('www');
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('user')
            ->add('name');
    }
}