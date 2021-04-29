<?php
require_once 'FP.php';

class Validator
{
    public static function required(Field $field)
    {
        if (!empty($field->error)) return;

        if (empty($field->value)) $field->error = 'Required';
    }

    public static function properLength(Field $field, int $min = 1, int $max = 255)
    {
        if (!empty($field->error)) return;

        if (strlen($field->value) < $min || strlen($field->value) > $max)
            $field->error = "Must be between $min and $max characters long.";
    }

    public static function matchPattern(Field $field, string $pattern, string $description)
    {
        if (!empty($field->error)) return;

        $match = preg_match($pattern, $field->value);
        if ($match === false) {
            $field->error = 'Error testing field.';
        } else if ($match != 1) {
            $field->error = $description;
        }
    }

    public static function checkConfirmPassword(Field $confirm_password, Field $password)
    {
        if (!empty($confirm_password->error)) return;

        if ($confirm_password->value !== $password->value)
            $confirm_password->error = 'Passwords do not match';
    }

    public static function allValid($fieldsArray)
    {
        return FP::every(function ($field) {
            return empty($field->error);
        }, $fieldsArray);
    }
}
