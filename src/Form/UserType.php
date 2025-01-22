<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',TextType::class, [
                'label' => 'username',
                'attr' => ['class' => 'form-control', 'placeholder'=>'username '],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false, // tsy ao anaty entity user io champ io
                'attr' => ['autocomplete' => 'new-password','class' => 'form-control','placeholder'=>'password '],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'first name',
                'attr' => ['class' => 'form-control', 'placeholder'=>'Firts name '],
            ])
            ->add('age', TextType::class, [
                'label' => 'Age',
                'attr' => ['class' => 'form-control', 'placeholder'=>'Age '],
            ])
               
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
