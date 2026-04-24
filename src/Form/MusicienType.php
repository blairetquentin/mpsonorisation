<?php 
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class MusicienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('instruments', CollectionType::class, [
                'entry_type' => InstrumentType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ]);
    }
}