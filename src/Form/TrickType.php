<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\User;
use App\Form\VideoType;
use App\Entity\Category;
use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image as img;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('Name', TextType::class, [
                'label' => 'Titre :',
                'attr' => [
                    'placeholder' => 'Titre du trick',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description :',
                'attr' => [
                    'placeholder' => 'Description du trick',
                ],
            ])
            ->add('picture', FileType::class, [
                'label' => 'Image principale :',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Img([
                        'maxSize' => '1M',
                        'mimeTypes' => ["image/jpeg", "image/jpg", "image/png"],
                        'mimeTypesMessage' => "Le fichier ne possède pas une extension valide ! Veuillez insérer une image en .jpg, .jpeg ou .png",
                    ])
                ]
            ])
            ->add('images', FileType::class, [
                'label' => 'Images secondaires :',
                'multiple' => true,
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new All([
                        new Img([
                            'maxSize' => '1M',
                            'mimeTypes' => ["image/jpeg", "image/jpg", "image/png"],
                            'mimeTypesMessage' => "Le fichier ne possède pas une extension valide ! Veuillez insérer une image en .jpg, .jpeg ou .png",
                        ])
                    ])
                ]
            ])
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false,
            ])
            ->add('categoryId', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'label' => 'Catégorie :',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
