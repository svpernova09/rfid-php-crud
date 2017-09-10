<?php

namespace App;

class Sanitizer
{
    public function __invoke($data)
    {
        $clean_data = [];

        foreach ((array)$data AS $key => $value)
        {
            $clean_data[$key] = filter_var($value, FILTER_SANITIZE_STRING);
        }

        return $clean_data;
    }
}