<?php
/**
 * Created by JetBrains PhpStorm.
 * User: denn
 * Date: 13/05/11
 * Time: 18:02
 * To change this template use File | Settings | File Templates.
 */

namespace Bean;


class MultiChoiceBean extends AbstractBean {

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