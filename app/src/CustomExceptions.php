<?php namespace CustomExceptions;

abstract class CustomExceptions extends \Exception {
    
    public function error_response() {
        return array("code" => $this->code,
                     "message" => $this->message);
    }
    
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
    
}

/*
class OrderIsDone extends CustomExceptions {
    
    public function __construct($message, $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
*/

class OrderNotFound extends CustomExceptions
{
    public function __construct($message, $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}


class InputDataNotValid extends CustomExceptions
{
    public function __construct($message, $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}


class ItemsNotFound extends CustomExceptions
{
    public function __construct($message, $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class DatabaseCreateOrderError extends CustomExceptions
{
    public function __construct($message, $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class AuthenticationFailed extends CustomExceptions
{
    public function __construct($message, $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}