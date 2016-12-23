<?php

namespace BananaStation\MusicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MusicType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('youtubeid', TextType::class)
            ->add('style', ChoiceType::class, array(
                'choices' => array(
                    'Games' => 'G',
                    'Trap' => 'T',
                    'Movie' => 'M',
                    'Electro & House' => 'E',
                    'Dubstep & Drum and bass' => 'D',
                    'Rock' => 'R'
                )))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BananaStation\MusicBundle\Entity\Music'
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'bananastation_musicbundle_music';
    }
}
