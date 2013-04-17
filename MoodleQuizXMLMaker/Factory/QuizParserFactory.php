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

    private function createParser($kind) {
        switch (true){
            case preg_match("/TorF/i", $kind):
                return new Parser¥TorFParser();
                break;
            case preg_match("/cloze/i", $kind):
                // 処理
                break;
            default:
                throw new ¥Exception("Quiz kind not recognized!");
        }
    }
}