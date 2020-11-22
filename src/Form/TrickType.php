<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\User;
use App\Form\ImageType;
use App\Form\VideoType;
use App\Entity\Category;
use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image as img;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            
            ->add('Name', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Titre du trick',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Description du trick',
                ],
            ])
            
            ->add('picture', FileType::class, [
                'label' => 'Image principale',
                'required' => false,
                'mapped' => false
                
            ])
            
            ->add('images', FileType::class, [
                'label' => 'Images secondaires',
                'multiple' => true,
                'required' => false,
                'mapped' => false
            ])

            ->add('videos', CollectionType::class, [
                'entry_type' => VideoType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false,
                  /*
                'prototype' => true,

                */
            ])
            ->add('categoryId', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'label' => 'Catégorie',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
