<?php

    //error handler
    function my_error_handler($errno, $errstr, $errfile, $errline) {
        if (error_reporting() === 0) {
            return false;
        }
        switch ($errno) {
            case E_NOTICE:
            case E_USER_NOTICE:
                $error = 'Notice';
                break;
            case E_WARNING:
            case E_USER_WARNING:
                $error = 'Warning';
                break;
            case E_ERROR:
            case E_USER_ERROR:
                $error = 'Fatal Error';
                echo '<br/>'.$error.'<br/>'.$errstr;
                break;
            default:
                $error = 'Unknown';
                break;
        }
        return true;
    }
    //exception handler
    function my_exception_handler($e){
        echo $e->getMessage();
    }
    // Error Handler
    set_error_handler('my_error_handler');
    set_exception_handler('my_exception_handler');
?>