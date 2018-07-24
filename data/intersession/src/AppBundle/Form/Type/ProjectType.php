<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 20/07/2018
 * Time: 16:51
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$builder->add('admin_id');
        $builder->add('name');
        $builder->add('description');
        $builder->add('price');
        $builder->add('cost');
        $builder->add('date_start');
        $builder->add('date_end');
        $builder->add('hour_pool');
        $builder->add('hour_spend');
        $builder->add('active');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Project',
            'csrf_protection' => false
        ]);
    }
}
