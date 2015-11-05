<?php

namespace BananaStation\MusicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MusicType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('youtubeid', 'text')
            ->add('style', 'choice', array(
                'choices' => array(
                    'G' => 'Games',
                    'H' => 'Hip-Hop',
                    'M' => 'Movie',
                    'E' => 'Electro & House',
                    'D' => 'Dubstep & Drum and bass',
                    'R' => 'Rock'
                )))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BananaStation\MusicBundle\Entity\Music'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bananastation_musicbundle_music';
    }
}
