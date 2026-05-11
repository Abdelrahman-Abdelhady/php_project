<?php

class Validator
{
    public $errors = [];

    public function required($field, $value, $message = null)
    {
        if (empty(trim((string)$value))) {
            $this->errors[$field] = $message ?? ucfirst(str_replace("_", " ", $field)) . " is required.";
        }
    }

    public function email($field, $value, $message = null)
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $message ?? "Invalid email format.";
        }
    }

    public function minLength($field, $value, $min, $message = null)
    {
        if (!empty($value) && strlen($value) < $min) {
            $this->errors[$field] = $message ?? ucfirst(str_replace("_", " ", $field)) . " must be at least $min characters.";
        }
    }

    public function maxLength($field, $value, $max, $message = null)
    {
        if (!empty($value) && strlen($value) > $max) {
            $this->errors[$field] = $message ?? ucfirst(str_replace("_", " ", $field)) . " cannot exceed $max characters.";
        }
    }

    public function phone($field, $value, $message = null)
    {
        if (!empty($value) && !preg_match('/^[0-9]{10,15}$/', $value)) {
            $this->errors[$field] = $message ?? "Phone number must contain only digits and be 10 to 15 numbers.";
        }
    }

    public function role($field, $value, $message = null)
    {
        $allowedRoles = ['driver', 'space_owner'];

        if (!in_array($value, $allowedRoles)) {
            $this->errors[$field] = $message ?? "Invalid role selected.";
        }
    }

    public function match($field, $value, $matchField, $matchValue, $message = null)
    {
        if ($value !== $matchValue) {
            $this->errors[$field] = $message ?? ucfirst($field) . " must match " . ucfirst($matchField) . ".";
        }
    }

    public function passes()
    {
        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}