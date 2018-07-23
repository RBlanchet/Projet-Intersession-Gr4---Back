<?php //src/AppBundle/Form/Validator/Constraint/UserEmailUniqueValidator.php

namespace AppBundle\Form\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UserEmailUniqueValidator extends ConstraintValidator
{
    public function validate($users, Constraint $constraint)
    {
        if (!($users instanceof \Doctrine\Common\Collections\ArrayCollection)){
            return;
        }

        $usersEmail = [];
        foreach ($users as $user){
            if (in_array($user->getEmail(), $usersEmail)){
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
                return;
            }
            else {
                $usersEmail[] = $user->getEmail();
            }
        }
    }
}