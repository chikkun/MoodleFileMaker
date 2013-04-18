<?php
/**
 * Created by JetBrains PhpStorm.
 * User: denn
 * Date: 13/04/18
 * Time: 17:36
 * To change this template use File | Settings | File Templates.
 */
require_once('../MoodleQuizXMLMaker/MoodleQuizXMLMaker.php');

//namespace ParserTest;


class TorFParserTest extends \PHPUnit_Framework_TestCase {

    private $parser;
    public function setup(){
    }

    public function testXmlWrite() {
        $maker = new \Maker\MoodleQuizXMLMaker("sample.txt");
        $factory = $maker->getFactory();

        $parser = $factory->create("truefalse");
        $parser->xmlWrite();
    }
}
