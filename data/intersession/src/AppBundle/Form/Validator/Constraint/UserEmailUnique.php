<?php //src/AppBundle/Form/Validator/UserEmailUnique.php


namespace AppBundle\Form\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Class UserEmailUnique
 * @Annotation
 */
class UserEmailUnique extends Constraint
{
    public $message = "A task cannot have users with same email";
}