<?php //src/AppBundle/Form/Type/SprintType.php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SprintType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('startAt', null, array(
            'mapped' => false,
        ));
        $builder->add('endAt', null, array(
            'mapped' => false,
        ));
        $builder->add('active', CheckboxType::class);
        $builder->add('tasks', CollectionType::class,[
            'entry_type'        => TaskType::class,
            'allow_add'         => true,
            'error_bubbling'    =>false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Meeting',
            'csrf_protection' => false
        ]);
    }
}