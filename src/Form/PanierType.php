<?php

namespace App\Form;

use App\Entity\Panier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PanierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_location', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de location',
            ])
            ->add('date_fin_location', DateType::class, [
            'widget' => 'single_text',
            'label' => 'Date de fin de location',
            ])
            ->add('adresse_location' , TextType::class, [
                'label' => 'adresse de location', 
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Panier::class,
            'csrf_protection' => false,
        ]);
    }
}
