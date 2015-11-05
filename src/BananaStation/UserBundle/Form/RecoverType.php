<?php

namespace BananaStation\UserBundle\Form;

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
        $builder->add('email', 'email', array(
            'constraints' => array(
                new Assert\NotNull(),
                new Assert\NotBlank(),
                new Assert\Email()
            )))
            ->add('recaptcha', 'ewz_recaptcha', array(
                'constraints' => array(
                    new Recaptcha\True()
                )));
    }

    public function getName() {
        return 'bananastation_userbundle_utilisateur_forget';
    }

}
