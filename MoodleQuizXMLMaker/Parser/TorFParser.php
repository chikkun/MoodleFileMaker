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
 * 真偽問題のXMLを作る。
 * Class TorFParser
 * @package Parser
 */
class TorFParser extends \Parser\AbstractParser
{
    private $defaultOption = array(
        "category" => "ルート",
        "name" => "問",
        "defaultgrade" => "1.0000000",
        "penalty" => "1.0000000",
        "hidden" => "0",
        "commonFeedback" => "",
        "ngFeedback" => "",
        "okFeedback" => "");

    public function __constructor()
    {

    }


    /**
     * 真偽問題のXMLを作る。
     * @param $bean        一つの問題を表すbean。
     * @return string      問題をXMLの書式で表した文字列を返す。
     * @throws \Exception　answer が 'T' 'F' 'true' 'false' のいずれでもないときにエラーを返す。
     */
    public function xmlWrite($bean)
    {
        mb_http_output("UTF-8");
        $writer = xmlwriter_open_memory();

        xmlwriter_set_indent($writer, 4);
        xmlwriter_set_indent_string($writer, "\t");
            $config = $bean->getConfig();
            $config = \Utility\UtilityStatics::mergeLargerToSmaller($config, $this->defaultOption);

            if (preg_match('/^T$|^true$/i', $bean->getAnswer())) {
                $t_fraction = 100;
                $t_feedback = $config->okFeedback;
                $f_fraction = 0;
                $f_feedback = $config->ngFeedback;
            } else if (preg_match('/^F$|^false$/i', $bean->getAnswer())) {
                $t_fraction = 0;
                $t_feedback = $config->ngFeedback;
                $f_fraction = 100;
                $f_feedback = $config->okFeedback;
            } else {
                //errer
                throw new \Exception("Answer must be \"T\" or \"F\" ,\"true\" or \"false\" !");
            }

// 一つのbean ここから
            xmlwriter_start_element($writer, "question");
            xmlwriter_write_attribute($writer, "type", "truefalse");

            xmlwriter_start_element($writer, "name");
            xmlwriter_start_element($writer, "text");
            xmlwriter_text($writer, "$config->name");
            xmlwriter_end_element($writer);
            xmlwriter_end_element($writer);

            xmlwriter_start_element($writer, "questiontext");
            xmlwriter_write_attribute($writer, "format", "html");
            xmlwriter_start_element($writer, "text");
            xmlwriter_write_cdata($writer, $bean->getQuestion() );
            xmlwriter_end_element($writer);
            xmlwriter_end_element($writer);

            xmlwriter_start_element($writer, "generalfeedback");
            xmlwriter_write_attribute($writer, "format", "html");
            xmlwriter_start_element($writer, "text");
        if("" == $config->commonFeedback) {
            xmlwriter_text($writer, "");
        } else {
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

            //true と答えたときの処理
            xmlwriter_start_element($writer, "answer");
            xmlwriter_write_attribute($writer, "fraction", $t_fraction);
            xmlwriter_write_attribute($writer, "format", "moodle_auto_format");
            xmlwriter_start_element($writer, "text");
            xmlwriter_text($writer, "true");
            xmlwriter_end_element($writer);
            xmlwriter_start_element($writer, "feedback");
            xmlwriter_write_attribute($writer, "format", "html");
            xmlwriter_start_element($writer, "text");
        if("" == $t_feedback) {
            xmlwriter_text($writer, "");
        } else {
            xmlwriter_write_cdata($writer, $t_feedback );
        }
            xmlwriter_end_element($writer);
            xmlwriter_end_element($writer);
            xmlwriter_end_element($writer);

            //false と答えたときの処理
            xmlwriter_start_element($writer, "answer");
            xmlwriter_write_attribute($writer, "fraction", $f_fraction);
            xmlwriter_write_attribute($writer, "format", "moodle_auto_format");
            xmlwriter_start_element($writer, "text");
            xmlwriter_text($writer, "false");
            xmlwriter_end_element($writer);
            xmlwriter_start_element($writer, "feedback");
            xmlwriter_write_attribute($writer, "format", "html");
            xmlwriter_start_element($writer, "text");
        if("" == $f_feedback) {
            xmlwriter_text($writer, "");
        } else {
            xmlwriter_write_cdata($writer, $f_feedback );
        }
            xmlwriter_end_element($writer);
            xmlwriter_end_element($writer);
            xmlwriter_end_element($writer);


            xmlwriter_end_element($writer);
// 一つのbean ここまで

        return xmlwriter_output_memory($writer);
    }

}

