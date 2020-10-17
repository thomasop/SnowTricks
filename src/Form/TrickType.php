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
//use Symfony\Component\Validator\Constraints\Image;
//use Symfony\Component\Validator\Constraints\File;
//use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EntityType::class,[
                'class' => User::class,
                'choice_label' => 'id',
            ])
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
            ->add('type', TextType::class, [
                'label' => 'Type',
                'attr' => [
                    'placeholder' => 'Type du trick',
                ],
            ])
            ->add('picture', FileType::class,[
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '5M',
                    ])
                ],
            ])

            ->add('video', TextType::class,[
                'label' => 'Video',
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}