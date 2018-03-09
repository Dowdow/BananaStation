<?php

namespace App\Form;

use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints as Assert;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;

class RecoverType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('email', EmailType::class, [
            'constraints' => [
                new Assert\NotNull(),
                new Assert\NotBlank(),
                new Assert\Email()
            ]])
            ->add('recaptcha', EWZRecaptchaType::class, [
                'constraints' => [
                    new Recaptcha\IsTrue()
                ]]);
    }

    public function getBlockPrefix() {
        return 'user_utilisateur_forget';
    }

}
