<?php
require_once 'FP.php';

class Validator
{
    public static function required(Field $field)
    {
        if (Field::hasError($field)) return;

        if (empty($field->value)) $field->error = 'Required';
    }

    public static function properLength(Field $field, int $min = 1, int $max = 255)
    {
        if (Field::hasError($field)) return;

        if (strlen($field->value) < $min || strlen($field->value) > $max)
            $field->error = "Must be between $min and $max characters long.";
    }

    public static function stringMatchPattern(string $value, string $pattern, string $description) : string
    {
        $match = preg_match($pattern, $value);
        if ($match === false) {
            return 'Error testing field.';
        } else if ($match != 1) {
            return $description;
        }
        return '';
    }

    public static function matchPattern(Field $field, string $pattern, string $description)
    {
        if (Field::hasError($field)) return;

        $field->error = self::stringMatchPattern($field->value, $pattern, $description);
    }

    public static function checkConfirmPassword(Field $confirm_password, Field $password)
    {
        if (Field::hasError($confirm_password)) return;

        if ($confirm_password->value !== $password->value)
            $confirm_password->error = 'Passwords do not match';
    }

    public static function checkEmail(Field $email_field)
    {
        if(Field::hasError($email_field)) return;

        // Split email address on @ sign and check parts
        $parts = explode('@', $email_field->value);
        if (count($parts) < 2) {
            $email_field->error = 'At sign required.';
            return;
        }
        if (count($parts) > 2) {
            $email_field->error = 'Only one at sign allowed.';
            return;
        }

        $local = $parts[0];
        $domain = $parts[1];

        // Check lengths of local and domain parts
        if (strlen($local) > 64) {
            $email_field->error = 'Username part too long.';
            return;
        }
        if (strlen($domain) > 255) {
            $email_field->error = 'Domain name part too long.';
            return;
        }

        // Patterns for address formatted local part
        $atom = '[[:alnum:]_!#$%&\'*+\/=?^`{|}~-]+';
        $dot_atom = '(\.' . $atom . ')*';
        $address = '(^' . $atom . $dot_atom . '$)';

        // Patterns for quoted text formatted local part
        $char = '([^\\\\"])';
        $esc  = '(\\\\[\\\\"])';
        $text = '(' . $char . '|' . $esc . ')+';
        $quoted = '(^"' . $text . '"$)';

        // Combined pattern for testing local part
        $localPattern = '/' . $address . '|' . $quoted . '/';

        // Call the pattern method and exit if it yields an error
        $email_field->error = Field::hasError($email_field) ?: self::stringMatchPattern(
            $local,
            $localPattern,
            'Invalid username part.'
        );

        // Patterns for domain part
        $hostname = '([[:alnum:]]([-[:alnum:]]{0,62}[[:alnum:]])?)';
        $hostnames = '(' . $hostname . '(\.' . $hostname . ')*)';
        $top = '\.[[:alnum:]]{2,6}';
        $domainPattern = '/^' . $hostnames . $top . '$/';

        // Call the pattern method
        $email_field->error = Field::hasError($email_field) ?: self::stringMatchPattern(
            $domain,
            $domainPattern,
            'Invalid domain name part.'
        );
    }

    public static function allValid($fieldsArray)
    {
        return FP::every(function ($field) {
            return empty($field->error);
        }, $fieldsArray);
    }
}
