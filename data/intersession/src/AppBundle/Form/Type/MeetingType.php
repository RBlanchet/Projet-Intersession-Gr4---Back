<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23/07/2018
 * Time: 14:49
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeetingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('description');
        $builder->add('organiser');
        $builder->add('location');
        $builder->add('startAt', null, array(
            'mapped' => false,
        ));
        $builder->add('endAt', null, array(
            'mapped' => false,
        ));
        $builder->add('active', CheckboxType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Meeting',
            'csrf_protection' => false
        ]);
    }

}