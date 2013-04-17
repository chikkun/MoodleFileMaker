<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chikkun
 * Date: 2013/03/20
 * Time: 9:00
 * To change this template use File | Settings | File Templates.
 */

namespace Exception;


class ConfigException extends \Exception {
    function __construct($message = NULL, $code = 0){
        parent::__construct($message, $code);
    }
}