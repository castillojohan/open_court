<?php

namespace App\Form;

use App\Entity\Member;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
                'label' => 'Date de naissance',
                'widget' => "choice",
                'format' => "dd MMMM yyyy"
            ])
            ->add('gender', ChoiceType::class, [
                'label' => "Genre",
                'choices' => [
                    'Homme' => false,
                    'Femme' => true
                ]
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
                $form = $event->getForm();
                $member = $event->getData();
                if($member->getId() !== null){
                    $form->add('pincode', null, [
                        "mapped" => false,
                        "attr" => [
                            "placeholder" => "Laisser vide si inchangé"
                        ]
                    ]);
                }
                else {
                    $form->add('pincode');
                }
            }
        );
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
