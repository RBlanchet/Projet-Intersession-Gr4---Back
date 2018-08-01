<?php // src/AppBundle/Form/Type/TaskType.php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('description');
        $builder->add('cost');
        $builder->add('parent');
        $builder->add('sprint');
        $builder->add('time_spend');
        $builder->add('status');
        $builder->add('start_at');
        $builder->add('end_at');
        $builder->add('users');
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Task',
            'csrf_protection' => false
        ]);
    }
}