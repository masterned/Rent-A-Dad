<?php

class Validator
{
    public static function checkText(
        $value,
        $required = true,
        $min = 1,
        $max = 255
    ) {
        if ($required && empty($value)) {
            return 'Required';
        } else {
            if (strlen($value) < $min)
                return 'Too short';
    
            if (strlen($value) > $max)
                return 'Too long';
        }

        return '';
    }
}
