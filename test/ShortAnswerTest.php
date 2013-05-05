<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Chiku
 * Date: 13/04/21
 * Time: 16:16
 */
require_once('../MoodleQuizXMLMaker/MoodleQuizXMLMaker.php');

//namespace ParserTest;


class ShortAnswerParserTest extends \PHPUnit_Framework_TestCase
{

    private $parser;

    public function setup()
    {
    }

    public function testShortAnswer(){
        $maker = new \Maker\MoodleQuizXMLMaker("sample-N09.txt");
        $xml = $maker->makeXML(1);
        echo $xml;
        $this->assertTrue(true);
    }


}
