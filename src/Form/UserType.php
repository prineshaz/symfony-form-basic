<?php
// src/Form/UserType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserType extends AbstractType 
{
  public  function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder->add('name', TextType::class)
            ->add('email', TextType::class)
            ->add('phone', TextType::class, [
              'attr' => [
                'placeholder' => 'Can use + for international numbers', 
                'maxlength' => 14,
                ]
            ])
            ->add('subscription', ChoiceType::class, [
                'choices' => [
                    'Free' => 'free',
                    'Premium' => 'premium',
                ],
            ]);
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults([
      'data_class' => \App\Entity\UserRegistration::class,
      'validation_groups' => function (FormInterface $form) {
        return ['step1'];
      }
    ]);
  }
}