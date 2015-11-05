<?php

namespace BananaStation\UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePasswordType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $constraints = array(
            'constraints' => array(
                new Assert\NotNull(),
                new Assert\NotBlank(),
                new Assert\Length(array(
                    'min' => '8',
                    'max' => '30',
                    'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
                    'maxMessage' => 'Votre mot de passe ne peut pas être plus long que {{ limit }} caractères'
                ))));

        $builder
            ->add('apassword', 'password', $constraints)
            ->add('npassword', 'password', $constraints);
    }

    public function getName() {
        return 'bananastation_userbundle_utilisateur_change_password';
    }
} 