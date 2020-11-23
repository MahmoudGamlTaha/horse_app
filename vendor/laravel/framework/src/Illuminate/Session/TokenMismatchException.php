<?php

namespace Illuminate\Session;

use Exception;
class TokenMismatchException extends Exception {

    public function report()
    {
        $error = array();
        $error['message'] = "miss API Token";
        $error['key'] = 401;
 
        return    $error;
    }
}
