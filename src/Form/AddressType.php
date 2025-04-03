<?php

// src/Form/AddressType.php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstLine')
            ->add('secondLine', TextType::class, [
                'required'   => false,
                'empty_data' => '',
                'attr'       => [
                    'placeholder' => 'Optional',
                ],
            ])
            ->add('city')
            ->add('state_province', TextType::class, [
                'required'   => false,
                'empty_data' => '',
                'attr'       => [
                    'placeholder' => 'Optional',
                ],
            ])
            ->add('country', CountryType::class, [
                'placeholder'       => 'Select a country',
                'preferred_choices' => ['FR', 'US', 'GB'],
            ])
            ->add('postCode');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'        => Address::class,
            'validation_groups' => function (FormInterface $form) {
                return ['address'];
            },
        ]);
    }
}
