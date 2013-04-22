<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chikkun
 * Date: 2013/03/16
 * Time: 15:44
 * To change this template use File | Settings | File Templates.
 */

namespace Bean;

class ClozeBean extends AbstractBean
{

    private $config; //stdClass
    private $question; //string;
    private $answer; //string

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
