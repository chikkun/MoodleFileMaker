<?php
/**
 * Created by JetBrains PhpStorm.
 * User: denn
 * Date: 13/04/18
 * Time: 17:36
 * To change this template use File | Settings | File Templates.
 */

//namespace ParserTest;


use Parser\TorFParser;

class TorFParserTest extends \PHPUnit_Framework_TestCase {

    private $parser;
    public function setup(){
    }

    public function testXmlWrite() {
        $parser = new TorFParser();
        $parser->xmlWrite();
    }
}
