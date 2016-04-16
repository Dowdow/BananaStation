<?php

namespace BananaStation\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('description', TextareaType::class)
            ->add('etat', ChoiceType::class, array(
                'choices' => array(
                    'E' => 'En cours',
                    'T' => 'Terminé',
                    'P' => 'En pause'
                )))
            ->add('progression', PercentType::class, array(
                'type' => IntegerType::class
            ))
            ->add('file', FileType::class, array('required' => false))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BananaStation\CoreBundle\Entity\Projet'
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'bananastation_corebundle_projet';
    }
}
