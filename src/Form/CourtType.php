<?php

namespace App\Form;

use App\Entity\Court;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('surface', ChoiceType::class, [
                'choices'=> [
                    'Dur' => 'dur',
                    'Terre battue' => 'terre battue',
                    'Gazon' => 'gazon',
                    'Synthétique' => 'synthétique'
                ]
            ])
            ->add('isFunctionnal', ChoiceType::class, [
                'label' => 'Est fonctionnel ?',
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'attr' => [
                    'class' => 'checkbox'
                ]
            ])
            ->add('isLighted', ChoiceType::class, [
                'label' => 'Est éclairé ?',
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'attr' => [
                    'class' => 'checkbox'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Court::class,
        ]);
    }
}
