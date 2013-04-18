<?php
namespace Maker;
use \Exception\FileNotFoundException;
require_once('../log4php/Logger.php');
require_once('../Zend/Loader/StandardAutoloader.php');
/**
 * Class MoodleQuizXMLMaker
 * 問題ファイルから、MoodleのXML形式の問題へ変換する
 * 中心的なクラス。
 * @package Maker
 */
class MoodleQuizXMLMaker
{
    /**
     * @var array 問題毎のConfig内容を入れる配列
     */
    private $qConfigs = array();
    private $qContents = array();
    private $filename;
    private $logger;
    private $errorMessages = "";
    private $qNumber = 0;
    private $factory;
    private $beans = array();

    /**
     * コンストラクター。
     * <ul>
     * <li> Log4PHPの初期化とAutoloaderの設定を行う。</br>
     * <li> クイズファイルから中身を読み取る。
     * <li> 中身を問題やConfigにチェックしながら、パースする。
     * </ul>
     * @param string $file 問題ファイル名(パスも)
     */
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
        $loader->registerNamespace('Factory', __DIR__  . '/Factory');
        $loader->register();
        $loader->registerNamespace('Bean', __DIR__  . '/Bean');
        $loader->register();

        $this->factory = new \Factory\QuizParserFactory();
        $contents = $this->getContents($file);
        $config = $this->checkContents($contents);
    }

    /**
     * 与えられたファイル名を探し、その中身を返す。
     * @param string $file ファイル名(パス含む)
     * @return string ファイルの内容(そのまま)
     * @throws \Exception\FileNotFoundException ファイルが見つからない場合にこの例外を送出
     */
    private function getContents($file)
    {
        if (!file_exists($file)) {
            throw new FileNotFoundException("File not found! filename:" . $file . ":filename");
        }
        $buf = file_get_contents($file);
        return $buf;
    }

    /**
     * 問題の中身を\Bean\AbstractBeansに\Bean\AbstractBeanをセットする。</br>
     * また、同時にconfigの内容をチェックし、最後まで探査した後に、最後にエラーが終わったら、
     * 理由をログに書き込んで、何もしないで終了する(例外を送出)。
     * @param $contents 問題ファイルの中身
     * @throws \Exception\ConfigException configの記述誤りがあったらこの例外を送出
     */
    private function checkContents($contents)
    {
        //最後の行は削除(次のsplitで空のレコードが出来ることを避ける)
        $str = preg_replace('/\n+$/', '', $contents);
        //空行で分割(2行でも同じ)
        $array = preg_split('/\n\n+/', $str);
        $this->qNumber = count($array);
        $cn = 0;
        $qn = 0;
        $beforeType = "";
        $configExist = 0;
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
                        $config->type = "description";
                        break;
                    default:
                        $erm = "Question NO." . $qn . "Question type(type) is not correct! type:" . $qtype . ":";
                        $this->logger->error($erm);
                        $this->errorMessages .= $erm;
                        continue;
                }
                $configExist = 1;
                //前のと同じだったら、定義されている一部のプロパティだけを書き換える
                if($beforeType === $config->type){
                    $this->logger->debug("same type!");
                    //$qnは1始まりなので、1つ前の配列は2を引く必要がある
                    $config = \Utility\UtilityStatics::mergeLargerToSmaller($config, $this->beans[$qn - 2]->getConfig());
                }
                $beforeType = $config->type;
            } else {
                //config行がない場合は、前の問題と同じ
                if( $qn > 1)
                {
                    //最初の行にconfigがないはずがない
                    if (!empty($this->beans)) {
                        //array_push($this->qConfigs, $this->qConfigs[$qn - 2]);
                        $config = $this->beans[$qn - 2]->getConfig();
                    }

                }
                $configExist = 0;
            }
            $bean = $this->factory->createBean($config->type);
            $bean->setConfig($config);

            $ans = "";//clozeには答えがない!
            $quizText = "";
            if($configExist === 0){
                $ar = preg_split("/\n/", $val);
            }
            foreach ($ar as $v) {
                if(preg_match("/ans|answer/i", $v)){
                    $v = preg_replace("/ans|answer/i", "", $v);
                    $ans = $v;
                } else {
                    $quizText .= $v;
                }
            }

            $bean->setAnswer($ans);
            $bean->setQuestion($quizText);
            array_push($this->beans, $bean);
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

        return $this->qContents;
    }

    public function getBeans()
    {
        return $this->beans;
    }

    public function getQuestionNumber()
    {
        return $this->qNumber;
    }
    public function getFactory()
    {
        return $this->factory;
    }
}
