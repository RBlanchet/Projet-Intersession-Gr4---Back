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
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname');
        $builder->add('lastname');
        $builder->add('plainPassword');
        $builder->add('email', EmailType::class);
        $builder->add('roles',CollectionType::class,[
        'entry_type'        => RoleType::class,
        'allow_add'         => true,
        'error_bubbling'    =>false,
    ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User',
            'csrf_protection' => false
        ]);
    }
}