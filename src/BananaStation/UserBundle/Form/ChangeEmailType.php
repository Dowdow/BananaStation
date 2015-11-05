<?php

namespace BananaStation\UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints as Assert;

class ChangeEmailType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $constraints = array(
            'constraints' => array(
                new Assert\NotNull(),
                new Assert\NotBlank(),
                new Assert\Email()
            ));

        $builder
            ->add('aemail', 'email', $constraints)
            ->add('nemail', 'email', $constraints);
    }

    public function getName() {
        return 'bananastation_userbundle_utilisateur_change_email';
    }
} 