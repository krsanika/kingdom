<?php

namespace Pristo\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use  Pristo\AdminBundle\Form\Type\QnaFileType;

class QnaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder->add('subject', 'text', array("required"=> true));        
        $builder->add('context', 'textarea', array("required"=> true));
        $builder->add('orderId', 'integer', array("required"=> true, "mapped"=>false));        
        $builder->add('송신하기','submit');
        $builder->add('files', 'collection', array('type' => new QnaFileType(), 'allow_add'=> true));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pristo\AdminBundle\Entity\Qna',
        ));
    }

    public function getName()
    {
        return 'qna';
    }
}