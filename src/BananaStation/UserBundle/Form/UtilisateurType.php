<?php

namespace BananaStation\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UtilisateurType extends AbstractType {

  /**
   * @param FormBuilderInterface $builder
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('username', 'text')
            ->add('email', 'email')
            ->add('password', 'password')
            ->add('recaptcha', 'ewz_recaptcha');
  }

  /**
   * @param OptionsResolverInterface $resolver
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
        'data_class' => 'BananaStation\UserBundle\Entity\Utilisateur'
    ));
  }

  /**
   * @return string
   */
  public function getName() {
    return 'bananastation_userbundle_utilisateur';
  }

}
