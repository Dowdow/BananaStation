<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints as Assert;

class ChangeEmailType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $constraints = [
            'constraints' => [
                new Assert\NotNull(),
                new Assert\NotBlank(),
                new Assert\Email()
            ]
        ];

        $builder
            ->add('aemail', EmailType::class, $constraints)
            ->add('nemail', EmailType::class, $constraints);
    }

    public function getBlockPrefix() {
        return 'user_utilisateur_change_email';
    }
} 