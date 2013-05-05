<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chikkun
 * Date: 2013/03/16
 * Time: 17:19
 * To change this template use File | Settings | File Templates.
 */

namespace Parser;

require_once "AbstractParser.php";

/**
 * cloze問題のXMLを作る。
 * Class ClozeParser
 * @package Parser
 */
class ClozeParser extends \Parser\AbstractParser
{
    private $defaultOption = array(
        "category" => "\$system\$/システム のデフォルト",
        "name" => "問",
        "defaultgrade" => "1.0000000",
        "penalty" => "0",
        "hidden" => "0",
        "commonFeedback" => "");

    public function __constructor()
    {

    }

    private function convert_cloze($text){
        preg_match_all("/\(\((.+?)\)\)/", $text, $multis);
        foreach($multis[1] as $m){
            if(preg_match("/(?<!\\\\)\|/", $m)){
                $choices = preg_split("/(?<!\\\\)\|/", $m);
                $ans = $choices[0];
                shuffle($choices);
                $txt = "";
                $n = 0;
                foreach($choices as $c){
                    $n++;
                    if(preg_match("/[^\\\\]#/", $c)){
                        $sharps = preg_split("/(?<!\\\\)#/", $c);
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

    /**
     * cloze問題のXMLを作る。
     * @param $bean        一つの問題を表すbean。
     * @return string      問題をXMLの書式で表した文字列を返す。
     */
    public function xmlWrite($bean, $markdown)
    {
        mb_http_output("UTF-8");
        $writer = xmlwriter_open_memory();

        xmlwriter_set_indent($writer, 4);
        xmlwriter_set_indent_string($writer, "\t");
        $config = $bean->getConfig();
        $config = \Utility\UtilityStatics::mergeLargerToSmaller($config, $this->defaultOption);

// 一つのbean ここから
        xmlwriter_start_element($writer, "question");
        xmlwriter_write_attribute($writer, "type", "cloze");
            xmlwriter_start_element($writer, "name");
                xmlwriter_start_element($writer, "text");
                xmlwriter_text($writer, "$config->name");
                xmlwriter_end_element($writer);
            xmlwriter_end_element($writer);

            xmlwriter_start_element($writer, "questiontext");
            xmlwriter_write_attribute($writer, "format", "html");
                xmlwriter_start_element($writer, "text");
        $text = $bean->getQuestion();
        if ($markdown === 1) {
            $text = $this->gfm($text);
        }
                xmlwriter_write_cdata($writer, $text);
                xmlwriter_end_element($writer);
            xmlwriter_end_element($writer);

            xmlwriter_start_element($writer, "generalfeedback");
            xmlwriter_write_attribute($writer, "format", "html");
                xmlwriter_start_element($writer, "text");
        if ("" == $config->commonFeedback) {
            xmlwriter_text($writer, "");
        } else {
            $cfeed = $config->commonFeedback;
            if ($markdown === 1) {
                $cfeed = $this->gfm($cfeed);
            }
            xmlwriter_write_cdata($writer, $cfeed);
        }
                xmlwriter_end_element($writer);
            xmlwriter_end_element($writer);

            xmlwriter_start_element($writer, "defaultgrade");
            xmlwriter_text($writer, $config->defaultgrade);
            xmlwriter_end_element($writer);

            xmlwriter_start_element($writer, "penalty");
            xmlwriter_text($writer, $config->penalty);
            xmlwriter_end_element($writer);

            xmlwriter_start_element($writer, "hidden");
            xmlwriter_text($writer, $config->hidden);
            xmlwriter_end_element($writer);

        xmlwriter_end_element($writer);

// 一つのbean ここまで

        return xmlwriter_output_memory($writer);
    }

}

