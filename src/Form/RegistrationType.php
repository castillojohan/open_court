<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,[
                    'label' => 'Adresse Email'
                ])
            ->add('password', PasswordType::class, [
                "label" => "Nouveau mot de passe",
                "mapped" => false,
                "attr" => [
                    "placeholder" => "Laisser vide si inchangé"
                ]
            ])
            ->add('confirmPassword', PasswordType::class, [
                "label" => "Confirmation du mot de passe",
                "mapped" => false,
                "attr" => [
                    "placeholder" => "Laisser vide si inchangé"
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csfr_token_id' => 'registration',
        ]);
    }
}
