<?php


namespace App\Responses;


class FailResponse
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
}