<?php


namespace iflow\template\exception;


use Throwable;

class TemplateViewNotFound extends \Exception
{

    public function __construct($message = "template file not exists", $code = 404, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}