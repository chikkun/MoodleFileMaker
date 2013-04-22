<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Chiku
 * Date: 13/04/21
 * Time: 16:16
 */
require_once('../MoodleQuizXMLMaker/MoodleQuizXMLMaker.php');

//namespace ParserTest;


class TorFParserTest extends \PHPUnit_Framework_TestCase
{

    private $parser;

    public function setup()
    {
    }

    public function testMulti(){
        $text = <<< 'EOF'
    require '((\\nokogiri|kanna|kanaduchi))'
    require '((albino|wrong#だめっしょ1|wrong2#ダメっしょ2))'
    require '((r~edcarpet#Good job!))'
     (("sakai"|/tomok{o}|ch~iku\#))
     ((20.1))
     ((sakai#"僕です"|chiku#これも"僕"です))
     ((sakai#僕で\#す|chiku#これも僕です))
EOF;
        $text = $this->multi($text);
        var_dump($text);
        $this->assertTrue(true);
    }

    private function multi($text){
        preg_match_all("/\(\((.+?)\)\)/", $text, $multis);
        foreach($multis[1] as $m){
            if(preg_match("/\|/", $m)){
                $choices = preg_split("/\|/", $m);
                $ans = $choices[0];
                shuffle($choices);
                $txt = "";
                $n = 0;
                foreach($choices as $c){
                    $n++;
                    if(preg_match("/[^\\\\]#/", $c)){
                        $sharps = preg_split("/#/", $c);
                        $sharps[1] = htmlentities($sharps[1]);
                        $c = implode("#", $sharps);
                    }
                    $c = preg_replace("/([~". preg_quote('"}{') ."])/", "\\\\\$1", $c);
                    $c = preg_replace("{([~". preg_quote('/') ."])}", "\\\\\$1", $c);
                    if($c === $ans){
                        $txt .= "=".$c;
                    } else {
                        if($n !== 1){
                            $txt .= "~".$c;
                        } else {
                            $txt .= $c;
                        }
                    }
                }
                $txt = "{1:MULTICHOICE:" . $txt . "}";
            } else {
                $txt = "";
                if(preg_match("/^[\d\.]+$/", $m)){
                    $txt .= "{1:NUMERICAL:=" . $m . "}";
                } else {
                    $txt .= "{1:SHORTANSWER:=" . $m . "}";
                }
            }
            $text = preg_replace("{".preg_quote("((".$m."))") . "}", $txt, $text);
        }
        return $text;
    }
}
