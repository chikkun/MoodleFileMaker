<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chikkun
 * Date: 2013/05/05
 * Time: 11:46
 * To change this template use File | Settings | File Templates.
 */

$text = file_get_contents("tmp.txt");

if(preg_match("/(?<!\\\\)\|/", $text)){
   echo "OK";
}

if(preg_match("/(?<=\\\\)\|/", $text)){
    echo "OK";
}

$array = preg_split("/(?<!\\\\)\|/", $text);

var_dump($array);