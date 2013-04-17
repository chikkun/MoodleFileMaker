<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chikkun
 * Date: 2013/03/16
 * Time: 13:53
 * To change this template use File | Settings | File Templates.
 */

namespace Parser;


/**
 * Class AbstractParser
 * @package parser
 * 問題形式毎にパースする方法が違うので、共通したものを入れ込む
 * 抽象クラス.
 */
abstract class AbstractParser {
    private $qNumber;
    private $qConfig = array();
    public $defaultOption;

    /*
    <question type="truefalse">
        <name>
          <text>問1</text>
        </name>
        <questiontext format="html">
          <text><![CDATA[<p>CoffeeScriptは美味しい。</p>]]></text>
        </questiontext>
        <generalfeedback format="html">
          <text><![CDATA[<p>CoffeeScriptは飲めませんよねｗ</p>]]></text>
        </generalfeedback>
        <defaultgrade>1.0000000</defaultgrade>
        <penalty>1.0000000</penalty>
        <hidden>0</hidden>
        <answer fraction="0" format="moodle_auto_format">
          <text>true</text>
          <feedback format="html">
            <text><![CDATA[<p>正解！</p>]]></text>
          </feedback>
        </answer>
        <answer fraction="100" format="moodle_auto_format">
          <text>false</text>
          <feedback format="html">
            <text><![CDATA[<p>はずれ！</p>]]></text>
          </feedback>
        </answer>
        <tags>
          <tag><text>JacaScript</text></tag></tags>
      </question>

     */
    /**
     * @return mixed
     * データを読み込んで、MoodleのXML形式で問題を作成する準備をする。
     * 具体的には問題ビーンを配列に入れ込む。
     */
    abstract public function parse($str);
    abstract public function getQuestionNumber();
    abstract protected  function checkConfig($str);
    abstract protected  function mergeConfig($array1, $array2);
}