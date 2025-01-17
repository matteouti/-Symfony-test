<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('name', TextType::class, [
            'label' => 'Name',
            'attr' => ['class' => 'form-control', 'placeholder'=>'Name product'],
        ])
        ->add('description', TextType::class, [
            'label' => 'Description',
            'attr' => ['class' => 'form-control',
                        'placeholder'=>'Description...', 
                        'rows' => 5,],
        ])
        ->add('price', null, [
            'label' => 'Price',
            'attr' => ['class' => 'form-control',   'placeholder'=>'Price', ],
        ])
        ->add('cover', FileType::class, [
            'label' => 'Product Cover (Image file)',
            'mapped' => false,
            'required' => false,
            'attr' => ['class' => 'form-control'],
            'constraints' => [
                new File([
                    'maxSize' => '2M',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid image (JPEG/PNG)',
                ]),
            ],
        ])
        ->add('stock', null, [
            'label' => 'Stock',
            'attr' => ['class' => 'form-control',   'placeholder'=>'Stock', ],
        ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
