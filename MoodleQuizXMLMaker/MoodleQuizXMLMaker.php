<?php
namespace Maker;
use \Exception\FileNotFoundException;
/**
 * Created by JetBrains PhpStorm.
 * User: chikkun
 * Date: 2013/03/17
 * Time: 11:58
 * To change this template use File | Settings | File Templates.
 */
require_once('../log4php/Logger.php');
require_once('../Zend/Loader/StandardAutoloader.php');
class MoodleQuizXMLMaker
{
    private $qConfigs = array();
    private $qContents = array();
    private $filename;
    private $logger;
    private $errorMessages = "";
    private $qNumber = 0;

    public function __construct($file)
    {
        $this->filename = $file;
        //logger
        \Logger::configure(__DIR__ . '/log4php.properties');
        $this->logger = \Logger::getLogger("xmlmaker_log");
        $this->logger->debug("logger made OK");

        $loader = new \Zend\Loader\StandardAutoloader(array('autoregister_zf' => true));
        $loader->registerNamespace('Parser', __DIR__ . '/Parser');
        $loader->register();
        $loader->registerNamespace('Root', __DIR__ );
        $loader->register();
        $loader->registerNamespace('Utility', __DIR__  . '/Utility');
        $loader->register();
        $loader->registerNamespace('Exception', __DIR__  . '/Exception');
        $loader->register();

        $contents = $this->getContents($file);
        $config = $this->checkContents($contents);
    }

    private function getContents($file)
    {
        if (!file_exists($file)) {
            throw new FileNotFoundException("File not found! filename:" . $file . ":filename");
        }
        $buf = file_get_contents($file);
        return $buf;
    }

    private function checkContents($contents)
    {
        //最後の行は削除
        $str = preg_replace('/\n+$/', '', $contents);
        //空行で分割
        $array = preg_split('/\n\n+/', $str);
        $this->qNumber = count($array);
        $cn = 0;
        $qn = 0;
        $beforeType = "";
        foreach ($array as $val) {
            $qn++;
            if (preg_match("/^config:(.*?)\n(.*)$/si", $val, $ar)) {
                if(!empty($ar[1]))
                {
                    $c = $ar[1];
                } else {
                //config:の後に何もない
                    $erm = "Question NO." . $qn . "'config:' found, but config contents is empty";
                    $this->logger->error($erm);
                    $this->errorMessages .= $erm;
                    continue;
                    //throw new Exception("'config:' found, but config contents is empty");
                }
                $cn++;
                $c = preg_replace("/config:/i", '', $c);
                $config = json_decode($c);
                //jsonをデコードしてNGだったら
                if (empty($config)) {
                    $erm = "Question NO." . $qn . "'config:' found, but config JSON is broken!";
                    $this->logger->error($erm);
                    $this->errorMessages .= $erm;
                    continue;
                }

                $qtype = $config->type;
                switch (true) {
                    case preg_match("/TorF/i", $qtype) || preg_match("/truefalse/i", $qtype):
                        //統一のため
                        $config->type = "truefalse";
                        break;
                    case preg_match("/cloze/i", $qtype) || preg_match("/anaume/i", $qtype):
                        $config->type = "cloze";
                        break;
                    case preg_match("/shortanswer/i", $qtype) || preg_match("/sans/i", $qtype):
                        $config->type = "shortanswer";
                        break;
                    case preg_match("/multichoice/i", $qtype) || preg_match("/mchoice/i", $qtype):
                        $config->type = "multichoice";
                        break;
                    case preg_match("/description/i", $qtype) || preg_match("/desc/i", $qtype):
                        $config->type = "multichoice";
                        break;
                    default:
                        $erm = "Question NO." . $qn . "Question type(type) is not correct! type:" . $qtype . ":";
                        $this->logger->error($erm);
                        $this->errorMessages .= $erm;
                        continue;
                }
                //前のと同じだったら、定義されている一部のプロパティだけを書き換える
                if($beforeType === $config->type){
                    $this->logger->debug("same type!");
                    $config = \Utility\UtilityStatics::mergeLargerToSmaller($config, $this->qConfigs[$qn - 2]);
                }
                array_push($this->qConfigs, $config);
                $beforeType = $config->type;
                array_push($this->qContents, $ar[2]);
            } else {
                //config行がない場合は、前の問題と同じ
                if( $qn > 1)
                {
                    //最初の行にconfigがないとエラーになる
                    if (!empty($this->qConfigs)) {
                        array_push($this->qConfigs, $this->qConfigs[$qn - 2]);
                    }
                }
                array_push($this->qContents, $val);
            }
        }
        //config:という行が1つもないか、あっても中身がなかった
        if ($cn == 0) {
            $erm = "No config! please write config(json) at 1st line at least.";
            $this->logger->error($erm);
            $this->errorMessages .= $erm;
        }
       if(!empty($this->errorMessages))
       {
           throw new \Exception\ConfigException("Config error occur! See convert.log");
       }

        var_dump($this->qContents);
    }

    public function getQConfigs()
    {
        return $this->qConfigs;
    }

    public function getQuestionNumber()
    {
        return $this->qNumber;
    }

}
