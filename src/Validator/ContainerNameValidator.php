<?php

namespace App\Validator;

final class ContainerNameValidator implements ValidatorInterface
{
    /**
     * Container name MUST be:
     *  - 3 to 63 Characters;
     * - Starts With Letter or Number;
     * - Contains Letters, Numbers, and Dash (-);
     * - Every Dash (-) Must Be Immediately Preceded and Followed by a Letter or Number
     */
    public const PATTERN = '/^(?=.{3,63}$)[a-z0-9]+(-[a-z0-9]+)*$/';
    public const ERROR_MESSAGE = 'The container name is invalid.';

    private bool $valid = true;
    private ?string $error = null;

    public function __construct(private readonly string $value)
    {
        $this->validate($value);
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    private function  validate(string $value): void
    {
        if (!preg_match(self::PATTERN, $value)) {
            $this->valid = false;
            $this->error = self::ERROR_MESSAGE;
        }
    }
}
