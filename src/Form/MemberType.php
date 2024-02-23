<?php

namespace App\Form;

use App\Entity\Member;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom de famille'
            ])
            ->add('birthday', DateType::class, [
                'label' => 'Date de naissance'
            ])
            ->add('gender', ChoiceType::class, [
                'label' => "Genre",
                'choices' => [
                    'Homme' => false,
                    'Femme' => true
                ]
            ])
            ->add('pincode', PasswordType::class, [
                "attr" => [
                    "placeholder" => "Laisser vide si pas de modification du code pin"
                ]
            ])
            ->add('submit', SubmitType::class, [
                "label" => "Enregistrer",
                "attr" => [
                    "class" => "button",
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csfr_token_id' => 'member',
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
