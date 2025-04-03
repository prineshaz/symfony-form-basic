<?php

// src/Form/PaymentType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          ->add('cardNumber', TextType::class, [
              'attr' => [
                  'class'       => 'form-control form-control-sm',
                  'maxlength'   => 19,
                  'placeholder' => 'Card Number',
              ],
          ])
          ->add('cvv', TextType::class, [
              'attr' => [
                  'class'       => 'form-control form-control-sm',
                  'maxlength'   => 3,
                  'placeholder' => 'CVV',
              ],
          ])
          ->add('expirationDate', TextType::class, [
              'attr' => [
                  'class'                  => 'form-control',
                  'placeholder'            => 'MM/YYYY',
                  'data-controller'        => 'datepicker',
                  'data-datepicker-target' => 'input',
              ],
          ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'        => \App\Entity\PaymentDetails::class,
            'validation_groups' => function (FormInterface $form) {
                return ['step3'];
            },
        ]);
    }
}
