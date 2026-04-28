<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

class EvenementsceneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomEvenement')
            ->add('nomArtiste')
            ->add('dateEvenement', DateType::class, [
                'widget' => 'single_text'
            ]);
    }
}