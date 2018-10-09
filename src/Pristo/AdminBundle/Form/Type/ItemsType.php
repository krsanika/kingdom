<?php

namespace Pristo\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Pristo\AdminBundle\Form\Type\ItemsFileType;
use Pristo\AdminBundle\Entity\Product;

class ItemsType extends AbstractType
{
    private $pArray;
    private $cArray;
    
    public function __construct($pArray, $cArray) {
        $this->pArray = $pArray;
        $this->cArray = $cArray;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder->add("pId", "choice", array("choices" => $this->pArray, 'mapped'=> false, "property_path" => "pid", "label" => false));
        $builder->add("cId", "choice", array("choices" => $this->cArray, 'mapped'=> false, "property_path" => "cid", "label" => false));
        $builder->add("category", "choice", array("choices" => Product::$category, "required" => true));
        $builder->add("descript", "textarea");
        $builder->add('files', 'collection', array('type' => new ItemsFileType(), 'allow_add'=> true));
        $builder->add("Add Items", "submit");
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pristo\AdminBundle\Entity\Items',
        ));
    }

    public function getName()
    {
        return 'items';
    }
}