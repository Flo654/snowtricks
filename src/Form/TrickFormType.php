<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\Category;

use App\Form\VideoFormType;
use App\Form\PictureFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrickFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class )
            ->add('description', TextareaType::class)
            ->add('category', EntityType::class, [
                'attr' => [
                    'class' => 'form-select'
                ],
                'placeholder' => '-- Choice a category --',
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('pictures', CollectionType::class, [
                'entry_type' => PictureFormType::class,
                "entry_options" => [
                    "label" => false,
                ],
                'allow_add' => true,
                'allow_delete'=>true,
                'by_reference' => false,
                'required' => false
                

            ])
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoFormType::class,
                "entry_options" => [
                    "label" => false,
                ],
                'allow_add' => true,
                'allow_delete'=>true,
                'by_reference' => false
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
