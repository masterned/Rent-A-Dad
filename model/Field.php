<?php

class Field
{
    public $name;
    public $type;
    public $error;
    public $value;

    public function __construct(
        string $name,
        string $type = 'text'
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->error = '';
        $this->value = '';
    }

    public static function hasError(Field $field) {
        return !empty($field->error);
    }
}
