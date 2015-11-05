<?php

namespace BananaStation\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjetType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text')
            ->add('description', 'textarea')
            ->add('etat', 'choice', array(
                'choices' => array(
                    'E' => 'En cours',
                    'T' => 'Terminé',
                    'P' => 'En pause'
                )))
            ->add('progression', 'percent', array(
                'type' => 'integer'
            ))
            ->add('file', 'file', array('required' => false))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BananaStation\CoreBundle\Entity\Projet'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bananastation_corebundle_projet';
    }
}
