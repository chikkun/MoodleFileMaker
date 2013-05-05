<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chikkun
 * Date: 2013/03/17
 * Time: 11:08
 * To change this template use File | Settings | File Templates.
 */

namespace Factory;


class QuizParserFactory {

    public function create ($kind) {
        $paser = $this->createParser($kind);
        return $paser;
    }

    public function createParser($kind) {
        switch (true){
            case preg_match("/truefalse/i", $kind):
                return new \Parser\TorFParser();
                break;
            case preg_match("/cloze/i", $kind):
                //暫定TODO
                return new \Parser\ClozeParser();
                break;
            case preg_match("/shortanswer/i", $kind);
                return new \Parser\ShortAnswerParser();
                break;
            case preg_match("/multichoice/i", $kind);
                break;
            case preg_match("/description/i", $kind);
                return new \Parser\DescriptionParser();
                break;
            default:
                throw new \Exception("Quiz kind not recognized at making parser!");
        }
        return null;
    }

    public function createBean($kind) {
        switch (true){
            case preg_match("/truefalse/i", $kind):
                return new \Bean\TorFBean();
                break;
            case preg_match("/cloze/i", $kind):
               // 暫定TODO
                return new \Bean\ClozeBean();
                break;
            case preg_match("/shortanswer/i", $kind);
                // 暫定TODO
                return new \Bean\ShortAnswerBean();
                break;
            case preg_match("/multichoice/i", $kind);
                // 暫定TODO
                return new \Bean\TorFBean();
                break;
            case preg_match("/description/i", $kind);
                // 暫定TODO
                return new \Bean\DescriptionBean();
                break;
            default:
                throw new \Exception("Quiz kind not recognized  at making bean!");
        }
        return null;
    }
}