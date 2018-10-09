<?php

namespace Pristo\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of FileType
 *
 * @author kerius
 */
class QnaFileType extends AbstractType{
    
   public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder->add('file');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pristo\AdminBundle\Entity\QnaFiles',
        ));
    }

    public function getName()
    {
        return 'qnaFile';
    }    
    
}
