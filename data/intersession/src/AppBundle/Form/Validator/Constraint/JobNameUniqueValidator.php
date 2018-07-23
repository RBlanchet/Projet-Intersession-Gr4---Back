<?php //src/AppBundle/Form/Validator/Constraint/JobNameUniqueValidator.php

namespace AppBundle\Form\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class JobNameUniqueValidator extends ConstraintValidator
{
    public function validate($jobs, Constraint $constraint)
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