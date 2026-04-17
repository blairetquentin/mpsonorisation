<?php

namespace App\Form;

use App\Entity\Scene;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SceneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_evenement', TextType::class, [
                'label' => 'Nom de l\'événement',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('date_evenement', DateType::class, [
                'label' => 'Date de l\'événement',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('nom_artiste', TextType::class, [
                'label' => 'Nom de l\'artiste',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary mt-3'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Scene::class,
        ]);
    }
    
}