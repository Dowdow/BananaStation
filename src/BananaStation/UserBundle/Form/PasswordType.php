<?php

namespace BananaStation\UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints as Assert;

class PasswordType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('password', \Symfony\Component\Form\Extension\Core\Type\PasswordType::class, [
                'constraints' => [
                    new Assert\NotNull(),
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => '8',
                        'max' => '30',
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
                        'maxMessage' => 'Votre mot de passe ne peut pas être plus long que {{ limit }} caractères'
                    ])
                ]
            ]
        );
    }

    public function getBlockPrefix() {
        return 'bananastation_userbundle_utilisateur_password';
    }

} 