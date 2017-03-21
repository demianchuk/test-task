<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @author Sergii Demianchuk <sergii.demianchuk@clicktrans.pl>
 */
class GitHubUrlConstraint extends Constraint
{
    public function validatedBy()
    {
        return 'git_hub_url_validator';
    }
}
