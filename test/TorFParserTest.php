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


class TorFParserTest extends \PHPUnit_Framework_TestCase
{

    private $parser;

    public function setup()
    {
    }

    public function getDOM(){
        $expected = new DOMDocument();
        $expected->loadXML('<quiz><question></question></question></quiz>');
    }

    public function testXmlWrite()
    {
        $maker = new \Maker\MoodleQuizXMLMaker("sample.txt");
        $factory = $maker->getFactory();
        //$beans = $maker->getBeans();

        $bean0 = new \Bean\TorFBean();
        $bean0->setConfig(json_decode('{"type":"TorF", "category":"初級 のデフォルト", "name":"問", "commonFeedback":"お疲れ", "ngFeedback":"はずれ", "okFeedback":"合格"}'));
        $bean0->setQuestion("CoffeeScriptは飲むととても美味しい。");
        $bean0->setAnswer("F");

        $bean1 = new \Bean\TorFBean();
        $bean1->setConfig(json_decode('{"type":"TorF", "category":"初級 のデフォルト", "name":"問"}'));
        $bean1->setQuestion("Java Teaはとても美味しい。");
        $bean1->setAnswer("true");

        $parser = $factory->create("truefalse");
        $xml = $parser->xmlWrite($bean1);
        echo $xml;
    }

    public function testXmlWriteErrer()
    {
        $maker = new \Maker\MoodleQuizXMLMaker("sample.txt");
        $factory = $maker->getFactory();
        //$beans = $maker->getBeans();

        $bean0 = new \Bean\TorFBean();
        $bean0->setConfig(json_decode('{"type":"TorF", "category":"初級 のデフォルト", "name":"問", "commonFeedback":"お疲れ", "ngFeedback":"はずれ", "okFeedback":"合格"}'));
        $bean0->setQuestion("CoffeeScriptは飲むととても美味しい。");
        $bean0->setAnswer("Fa");

        $bean1 = new \Bean\TorFBean();
        $bean1->setConfig(json_decode('{"type":"TorF", "category":"初級 のデフォルト", "name":"問", "commonFeedback":"お疲れ", "ngFeedback":"はずれ", "okFeedback":"合格"}'));
        $bean1->setQuestion("Java Teaはとても美味しい。");
        $bean1->setAnswer("true");

        $parser = $factory->create("truefalse");
        $xml = $parser->xmlWrite($bean1);
        //$this->assertEquals();
        echo $xml;
    }


}
