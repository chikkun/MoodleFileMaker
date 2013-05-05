<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chikkun
 * Date: 2013/05/05
 * Time: 11:13
 * To change this template use File | Settings | File Templates.
 */

namespace Bean;


class Answer {
    private $answer = "";
    private $feedback = "";
    //当面100のみ
    private $fraction = 100;

    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;
    }

    public function getFeedback()
    {
        return $this->feedback;
    }

    public function setFraction($fraction)
    {
        $this->fraction = $fraction;
    }

    public function getFraction()
    {
        return $this->fraction;
    }

    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    public function getAnswer()
    {
        return $this->answer;
    }
}