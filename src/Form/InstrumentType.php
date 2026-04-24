<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class InstrumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option): void
    {
        $builder->add('nom');
    }
    
}