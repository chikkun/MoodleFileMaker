<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chikkun
 * Date: 2013/03/16
 * Time: 13:53
 */

namespace Parser;

require_once "../Beautify/beautify.php";

/**
 * Class AbstractParser
 * @package parser
 * 問題形式毎にパースする方法が違うので、共通したものを入れ込む
 * 抽象クラス.
 * protectedのgfmは実装(Github Fravoured Markdown)。
 */
abstract class AbstractParser {
    private $defaultOption;
    /**
     * @return mixed
     * データを読み込んで、MoodleのXML形式で問題を作成する準備をする。
     * 具体的には問題ビーンを配列に入れ込む。
     */
    abstract public function xmlWrite($bean, $markdown);

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
    protected function gfm($text)
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
}