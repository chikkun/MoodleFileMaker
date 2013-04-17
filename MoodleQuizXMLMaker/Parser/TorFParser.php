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
    public  $defaultOption = array("type" => "TorF",
        "category" => "ルート",
        "name" => "問",
        "name" => "穴埋め問題",
        "ngFeedback" => "Wrong!",
        "okFeedBack" => "OK!");
    private $qNumber;
    private $qConfig = array();

    public function __constructor()
    {

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

    public function getQuestionNumber()
    {
        return $this->qNumber;
    }

    protected function mergeConfig($op, $op2)
    {
        //値があれば上書き無い場合は初期値で上書き
        foreach ($op2 as $key => $val) {
            if (isset($arr1[$key])) {
                if (is_array($val)) {
                    $this->mergeConfig($op[$key], $val);
                } else {
                    $op[$key] = $val;
                }
            }
        }
        return $op;
    }

    protected function checkConfig($json)
    {
        foreach ($json as $key => $val) {

        }
    }
}

