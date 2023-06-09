<?php declare(strict_types=1);

class Run
{
    public $id;
    public $name;
    public $length;

    public function __construct($id, $name, $length)
    {
        $this->id = $id;
        $this->name = $name;
        $this->length = $length;
    }
}