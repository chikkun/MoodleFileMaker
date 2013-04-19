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

class TorFParser extends \Parser\AbstractParser
{
    public  $defaultOption = array(
        "type" => "TorF",
        "category" => "ルート",
        "name" => "問",
//        "name" => "穴埋め問題",
        "ngFeedback" => "Wrong!",
        "okFeedBack" => "OK!");
    private $qNumber;
    private $qConfig = array();

    public function __constructor()
    {

    }


    public function xmlWrite() {
        mb_http_output("UTF-8");

        $writer = xmlwriter_open_memory();

        xmlwriter_set_indent( $writer, 4 );
        xmlwriter_set_indent_string( $writer, "\t" );
        xmlwriter_start_document( $writer, "1.0", "UTF-8" );

        xmlwriter_start_element( $writer, "question" );
        xmlwriter_write_attribute( $writer ,"type" , "truefalse" );

        xmlwriter_start_element( $writer, "name" );
        xmlwriter_start_element( $writer, "text" );
        xmlwriter_text( $writer, "正誤問題1" );
        xmlwriter_end_element( $writer );
        xmlwriter_end_element( $writer );

        xmlwriter_start_element( $writer, "questiontext" );
        xmlwriter_write_attribute( $writer ,"format" , "html" );
        xmlwriter_start_element( $writer, "text" );
        xmlwriter_text( $writer, "<![CDATA[<p>私はバカです。</p>]]>" );
        xmlwriter_end_element( $writer );
        xmlwriter_end_element( $writer );

        xmlwriter_start_element( $writer, "generalfeedback" );
        xmlwriter_write_attribute( $writer ,"format" , "html" );
        xmlwriter_start_element( $writer, "text" );
        xmlwriter_text( $writer, "<![CDATA[<p>ほよ？</p>]]>" );
        xmlwriter_end_element( $writer );
        xmlwriter_end_element( $writer );

        xmlwriter_start_element( $writer, "defaultgrade" );
        xmlwriter_text( $writer, "1.0000000" );
        xmlwriter_end_element( $writer );

        xmlwriter_start_element( $writer, "penalty" );
        xmlwriter_text( $writer, "1.0000000" );
        xmlwriter_end_element( $writer );

        xmlwriter_start_element( $writer, "hidden" );
        xmlwriter_text( $writer, "0" );
        xmlwriter_end_element( $writer );

        xmlwriter_start_element( $writer, "answer" );
        xmlwriter_write_attribute( $writer ,"fraction" , "100" );
        xmlwriter_write_attribute( $writer ,"format" , "moodle_auto_format" );
        xmlwriter_start_element( $writer, "text" );
        xmlwriter_text( $writer, "true" );
        xmlwriter_end_element( $writer );
        xmlwriter_start_element( $writer, "feedback" );
        xmlwriter_write_attribute( $writer ,"format" , "html" );
        xmlwriter_start_element( $writer, "text" );
        xmlwriter_text( $writer, "<![CDATA[<p>そんなわけないだろ！</p>]]>" );
        xmlwriter_end_element( $writer );
        xmlwriter_end_element( $writer );
        xmlwriter_end_element( $writer );

        xmlwriter_start_element( $writer, "answer" );
        xmlwriter_write_attribute( $writer ,"fraction" , "100" );
        xmlwriter_write_attribute( $writer ,"format" , "moodle_auto_format" );
        xmlwriter_start_element( $writer, "text" );
        xmlwriter_text( $writer, "false" );
        xmlwriter_end_element( $writer );
        xmlwriter_start_element( $writer, "feedback" );
        xmlwriter_write_attribute( $writer ,"format" , "html" );
        xmlwriter_start_element( $writer, "text" );
        xmlwriter_text( $writer, "<![CDATA[<p>良く認識している！</p>]]>" );
        xmlwriter_end_element( $writer );
        xmlwriter_end_element( $writer );
        xmlwriter_end_element( $writer );


        xmlwriter_end_element( $writer );


        xmlwriter_end_element( $writer );
        xmlwriter_end_element( $writer );

        xmlwriter_end_document( $writer );
        echo xmlwriter_output_memory( $writer );
    }



    public function parse($str)
    {
        //最後の行は削除
        $str = preg_replace('/\n+$/', '', $str);
        //空行で分割
        $array = preg_split('/\n\n/', $str);
        $this->qNumber = count($array);
        $cn = 0;
        foreach ($array as $val) {
            if (preg_match("/^config:(.+)\n/i", $val, $ar)) {
                $cn++;
                $c = $ar[0];
                if (strlen($c) == 0) {
                    throw new \Exception("config not found");
                }
                $c = preg_replace("/config:/i", '', $c);
                $config = json_decode($c);
                $config = $this->mergeConfig($config, $this->defaultOption);
                array_push($this->qConfig, $config);
            }
        }

    }

    protected function checkConfig($json)
    {
        foreach ($json as $key => $val) {

        }
    }
}

