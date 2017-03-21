<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates git url.
 *
 * @Annotation
 *
 * @author Sergii Demianchuk <sergii.demianchuk@clicktrans.pl>
 */
class GitHubUrlValidator extends ConstraintValidator
{
    const GIT_URL_HOST = 'github.com';
    const GIT_URL_SCHEME = 'https';

    /**
     * @var TranslatorInterface
     */
    private $translator;


    public function __construct($translator)
    {
        $this->translator = $translator;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param string $value
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $host = parse_url($value, PHP_URL_HOST);
            $protocol = parse_url($value, PHP_URL_SCHEME);

            if ($host != self::GIT_URL_HOST || $protocol != self::GIT_URL_SCHEME) {
                $this->context->buildViolation(
                    $this->translator->trans('wrong_git_host', [], 'validators')
                )
                ->addViolation();

                return;
            }

            $headers = @get_headers($value);

            if (strpos($headers[0], '200')) {
                return;
            }
        }

        $this->context->buildViolation(
            $this->translator->trans('wrong_git_url', [], 'validators')
        )
        ->addViolation();
    }
}
