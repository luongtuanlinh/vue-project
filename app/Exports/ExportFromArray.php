<?php


namespace App\Exports;


class ExportFromArray
{
    public function __construct($array=[])
    {
        $this->data = $array;
    }

    public function array(): array
    {
        return $this->data;
    }
}