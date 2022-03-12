<?php

namespace App\Form;

use App\Entity\CodeTva;
use App\Entity\Produit;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name') ->add('ref') ->add('description')
            ->add('prix',TextType::class,['label' => ' Le prix est TTC '])
            ->add('quantity')
            ->add('image', FileType::class,
            [
                'label' =>'Chargez une image',
                'data_class' => null,
                'required' => false
            ])
            ->add('category', EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'name'

            ])
            ->add('tva', EntityType::class,[
                'class' => CodeTva::class,
                'choice_label' => 'code'

            ])
          
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
