<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chikkun
 * Date: 2013/04/20
 * Time: 9:47
 * To change this template use File | Settings | File Templates.
 */

require_once('../MoodleQuizXMLMaker/MoodleQuizXMLMaker.php');

$options = getopt('f:');

if(empty($options)){
    echo "Option '-f filename' is required!\n";
    exit;
}

$filename = $options['f'];

$maker = new \Maker\MoodleQuizXMLMaker($filename);

$xml = $maker->makeXML();

echo $xml;