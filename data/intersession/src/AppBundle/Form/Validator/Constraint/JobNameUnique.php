<?php //src/AppBundle/Form/Validator/UserEmailUnique.php


namespace AppBundle\Form\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Class JobNameUnique
 * @Annotation
 */
class JobNameUnique extends Constraint
{
    public $message = "A job cannot have same name as another";
}