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
    private $defaultOption;
    /**
     * @return mixed
     * データを読み込んで、MoodleのXML形式で問題を作成する準備をする。
     * 具体的には問題ビーンを配列に入れ込む。
     */
    abstract public function xmlWrite($bean, $markdown);
}