<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chikkun
 * Date: 2013/05/04
 * Time: 13:44
 */

namespace Bean;

class ShortAnswerBean extends AbstractBean
{

    private $config; //stdClass
    private $question; //string;
    private $answer; //Array

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function setQuestion($question)
    {
        $this->question = $question;
    }

    public function getQuestion()
    {
        return $this->question;
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
