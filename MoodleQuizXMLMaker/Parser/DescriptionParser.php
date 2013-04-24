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
require_once "../Beautify/beautify.php";

/**
 * 説明(Description)のXMLを作る。
 * Class DescriptionParser
 * @package Parser
 */
class DescriptionParser extends \Parser\AbstractParser
{
    private $defaultOption = array(
        "category" => "ルート",
        "name" => "問",
        "defaultgrade" => "1.0000000",
        "penalty" => "1.0000000",
        "hidden" => "0",
        "commonFeedback" => "");

    public function __constructor()
    {

    }

    /**
     * スタンダードなMarkdownに以下のようないくつかのGFM(Github Flavoured Markdown)
     * を加えた仕様で、HTMLに変換する。
     * <ul>
     *   <li>http://www.chikkun.com等のURLは自動でリンクにする。
     *   <li>「```php〜```」でコードのhighlightを付ける(。
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
                //$ln = "\n" . $ln . "\n";
            } else if(preg_match("/^[ \t]+$/", $ln)){
                $ln = "";
            }
            $ln = preg_replace("/(?<!\()((?:https?|ftp):\/\/[-_.!~*\'\(\)a-zA-Z0-9;\/?:\@&=+\$,%#]+)\b/", "[$0]($0)", $ln);
            if ($flg === 1 || preg_match("/^\|.+\|/", $ln)) {
                $text .= $ln . "\n";
            } else {
                $text .= $ln . "\n\n";
            }
        }
        //echo $text;
        //eval($text);
        return beautify($text);
    }

    /**
     * 説明のXMLを作る。
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
        xmlwriter_write_attribute($writer, "type", "description");
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
            xmlwriter_write_cdata($writer, $config->commonFeedback);
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

