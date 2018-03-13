<?php

namespace App\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\MissingOptionsException;

class PasswordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws MissingOptionsException
     * @throws InvalidOptionsException
     * @throws ConstraintDefinitionException
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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

    /**
     * @return null|string
     */
    public function getBlockPrefix(): ?string
    {
        return 'user_utilisateur_password';
    }

} 