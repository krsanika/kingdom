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
class ItemsFileType extends AbstractType{
    
   public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder->add('file');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pristo\AdminBundle\Entity\ItemsFiles',
        ));
    }

    public function getName()
    {
        return 'ItemsFile';
    }    
    
}
