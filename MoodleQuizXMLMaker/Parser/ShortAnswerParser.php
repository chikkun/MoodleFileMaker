<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chikkun
 * Date: 2013/05/04
 * Time: 13:50
 * To change this template use File | Settings | File Templates.
 */

namespace Parser;

require_once "AbstractParser.php";
require_once "../Beautify/beautify.php";

/**
 * 記述問題(shortanswer)のXMLを作る。
 * 当面fractionは100のみ対応。
 * Class ShortAnswerParser
 * @package Parser
 */
class ShortAnswerParser extends \Parser\AbstractParser
{
    private $defaultOption = array(
        "category" => "ルート",
        "name" => "問",
        "defaultgrade" => "1.0000000",
        "penalty" => "0",
        "hidden" => "0",
        "usecase" => "0",
        "hint" => "",
        "tag" => "",
        "commonFeedback" => "");

    public function __constructor()
    {

    }

    private function analyzeAnswers($text) {
        $answers = array();
        //答えが1つしかない
        if(!preg_match("/(?<!\\\\)\|/", $text)){
            $ans = new \Bean\Answer();
            if(!preg_match("/(?<!\\\\)\#/", $text)){
                $ans->setAnswer($text);
            } else {
                $ansFeed = preg_split("/(?<!\\\\)\#/", $text);
                $ans->setAnswer($ansFeed[0]);
                $ans->setFeedback($ansFeed[1]);
            }
            $answers[] = $ans;
            return $answers;
        }

        $anses = preg_split("/(?<!\\\\)\|/", $text);
        var_dump($anses);
        foreach($anses as $a) {
            $ans = new \Bean\Answer();
            if(!preg_match("/(?<!\\\\)\#/", $a)){
                $ans->setAnswer($a);
            } else {
                $ansFeed = preg_split("/(?<!\\\\)\#/", $a);
                $ans->setAnswer($ansFeed[0]);
                $ans->setFeedback($ansFeed[1]);
            }
            $answers[] = $ans;
        }
        return $answers;
    }

    /**
     * スタンダードなMarkdownに以下のようないくつかのGFM(Github Flavoured Markdown)
     * を加えた仕様で、HTMLに変換する。
     * <ul>
     *   <li>http://www.chikkun.com等のURLは自動でリンクにする。
     *   <li>「```php〜```」でコードのhighlightを付ける。
     *   <li>改行(空行ではない)が、そのまま段落になる。
     * </ul>
     * @param string $text 変換前のもの
     * @return string htmlに変換したもの
     */
    private function gfm($text)
    {
        $text = preg_replace("/```+/s", "~~~", $text);
        //$text = preg_replace("/```+/s", "\n~~~\n\n", $text);
        $lines = preg_split("/\n/", $text);
        $text = "";
        $flg = 0;

        foreach ($lines as $ln) {
            if (preg_match("/^~~~+/", $ln)) {
                if ($flg === 0) {
                    $flg = 1;
                } else {
                    $flg = 0;
                }
            } else if(preg_match("/^[ \t]+$/", $ln)){
                $ln = "";
            }
            $ln = preg_replace("/[^\(]*((?:https?|ftp):\/\/[-_.!~*\'\(\)a-zA-Z0-9;\/?:\@&=+\$,%#]+)\b/", "[$0]($0)", $ln);
            if ($flg === 1 || preg_match("/^\|.+\|/", $ln)) {
                $text .= $ln . "\n";
            } else {
                $text .= $ln . "\n\n";
            }
        }
        return beautify($text);
    }

    /**
     * 記述問題(shortanswer)のXMLを作る。
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
        xmlwriter_write_attribute($writer, "type", "shortanswer");
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

            xmlwriter_start_element($writer, "usecase");
            xmlwriter_text($writer, $config->usecase);
            xmlwriter_end_element($writer);

            $array = $this->analyzeAnswers($bean->getAnswer());
            foreach($array as $a) {
                xmlwriter_start_element($writer, "answer");
                xmlwriter_write_attribute($writer, "fraction", $a->getFraction());
                xmlwriter_write_attribute($writer, "format", "moodle_auto_format");
                    xmlwriter_start_element($writer, "text");
                    xmlwriter_text($writer, $a->getAnswer());
                    xmlwriter_end_element($writer);

                    xmlwriter_start_element($writer, "feedback");
                    xmlwriter_write_attribute($writer, "format", "html");
                    xmlwriter_write_cdata($writer, $this->gfm($a->getFeedback()));
                    xmlwriter_end_element($writer);

                xmlwriter_end_element($writer);
            }
        if(is_array($config->hint)){
            foreach($config->hint as $h) {
                xmlwriter_start_element($writer, "hint");
                xmlwriter_write_attribute($writer, "format", "html");
                xmlwriter_write_cdata($writer, $this->gfm($h));
                xmlwriter_end_element($writer);
            }
        } else {
            xmlwriter_start_element($writer, "hint");
            xmlwriter_write_attribute($writer, "format", "html");
            xmlwriter_write_cdata($writer, $this->gfm($config->hint));
            xmlwriter_end_element($writer);
        }
        xmlwriter_start_element($writer, "tags");
        if(is_array($config->tag) && !empty($config->tag)){
            foreach($config->tag as $t) {
                xmlwriter_start_element($writer, "tag");
                xmlwriter_start_element($writer, "text");
                    xmlwriter_text($writer, $t);
                xmlwriter_end_element($writer);
                xmlwriter_end_element($writer);
            }
        } else if(!empty($config->tag)){
            xmlwriter_start_element($writer, "tag");
            xmlwriter_start_element($writer, "text");
            xmlwriter_write_cdata($writer, $config->tag);
            xmlwriter_end_element($writer);
            xmlwriter_end_element($writer);
        }
        xmlwriter_end_element($writer);

        xmlwriter_end_element($writer);
        return xmlwriter_output_memory($writer);
    }

}

