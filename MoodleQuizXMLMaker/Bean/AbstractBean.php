<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chikkun
 * Date: 2013/03/16
 * Time: 14:04
 * To change this template use File | Settings | File Templates.
 */

namespace Bean ;

    /**
     * Class AbstractBean
     * @package beans
     * 問題1つに対して、1つのこれをextendsしたクラスが担当する。
     *
     */
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
     *
     */
    abstract class AbstractBean {

        private $config ;  //stdClass
        private $question ;//string;
        private $answer ;  //string


    }

