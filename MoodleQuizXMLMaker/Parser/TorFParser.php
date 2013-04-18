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

        xmlwriter_start_element( $writer, "root" );
        xmlwriter_start_element( $writer, "aaaaaaaa" );
        xmlwriter_start_element( $writer, "test" );

        xmlwriter_write_attribute( $writer , "moge" , "mogemoge" );
        xmlwriter_text( $writer, "ほげほげ" );

        xmlwriter_end_element( $writer );

        xmlwriter_start_element( $writer, "hage" );

        xmlwriter_write_attribute( $writer , "moge" , "mogemoge" );
        xmlwriter_text( $writer, "ほげほげ" );

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

