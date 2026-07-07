<?php

class CMB2
{
    public array $args = [];

    public array $fields = [];

    public function __construct(array $args = [])
    {
        $this->args = $args;
    }

    public function add_field(array $field)
    {
        $this->fields[] = $field;
    }
}
