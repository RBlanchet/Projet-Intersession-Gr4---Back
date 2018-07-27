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

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$builder->add('admin_id');
        $builder->add('name');
        $builder->add('description');
        $builder->add('price');
        $builder->add('cost');
        $builder->add('hour_pool');
        //$builder->add('hour_spend');
        $builder->add('date_start', null, array(
            'mapped' => false,
        ));
        $builder->add('date_end', null, array(
            'mapped' => false,
        ));
        //$builder->add('active', CheckboxType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Project',
            'csrf_protection' => false
        ]);
    }
}
