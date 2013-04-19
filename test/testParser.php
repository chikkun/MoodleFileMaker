<?php
require_once('../MoodleQuizXMLMaker/MoodleQuizXMLMaker.php');
//error_reporting(E_ALL & ~E_NﬁOTICE);
class StackTest extends PHPUnit_Framework_TestCase
{
    protected function  setUp()
    {
    }

    public function testNormalFiles()
    {
        //Normal 1 正誤問題---2問目にはconfigなし
        $maker = new \Maker\MoodleQuizXMLMaker("sample.txt");
        $configs = $maker->getBeans();
        //1問目
        $this->assertEquals(2, count($configs));
        $this->assertEquals('truefalse', $configs[0]->getConfig()->type);
        $this->assertEquals('初級 のデフォルト', $configs[0]->getConfig()->category);
        $this->assertEquals('問', $configs[0]->getConfig()->name);
        $this->assertEquals('お疲れ', $configs[0]->getConfig()->commonFeedback);
        $this->assertEquals('はずれ', $configs[0]->getConfig()->ngFeedback);
        $this->assertEquals('合格', $configs[0]->getConfig()->okFeedback);
        $this->assertEquals('合格', $configs[0]->getConfig()->okFeedback);
        $this->assertEquals('CoffeeScriptは飲むととても美味しい。', $configs[0]->getQuestion());
        //2問目
        $this->assertEquals('truefalse', $configs[1]->getConfig()->type);
        $this->assertEquals('初級 のデフォルト', $configs[1]->getConfig()->category);
        $this->assertEquals('問', $configs[1]->getConfig()->name);
        $this->assertEquals('お疲れ', $configs[1]->getConfig()->commonFeedback);
        $this->assertEquals('はずれ', $configs[1]->getConfig()->ngFeedback);


        //Normal 2 正誤問題---2問目にもconfigあり
        $maker = new \Maker\MoodleQuizXMLMaker("sample-N01.txt");
        $configs = $maker->getBeans();
        //1問目
        $this->assertEquals(2, count($configs));
        $this->assertEquals('truefalse', $configs[0]->getConfig()->type);
        $this->assertEquals('初級 のデフォルト', $configs[0]->getConfig()->category);
        $this->assertEquals('問', $configs[0]->getConfig()->name);
        $this->assertEquals('お疲れ', $configs[0]->getConfig()->commonFeedback);
        $this->assertEquals('はずれ', $configs[0]->getConfig()->ngFeedback);
        $this->assertEquals('合格', $configs[0]->getConfig()->okFeedback);
        //2問目
        $this->assertEquals('truefalse', $configs[1]->getConfig()->type);
        $this->assertEquals('問', $configs[1]->getConfig()->name);
        $this->assertEquals('初級 のデフォルト', $configs[1]->getConfig()->category);
        $this->assertEquals('ご苦労!', $configs[1]->getConfig()->commonFeedback);
        $this->assertEquals('はずれ', $configs[1]->getConfig()->ngFeedback);
        $this->assertEquals('合格', $configs[1]->getConfig()->okFeedback);

        //Normal 3 cloze問題---2問目にはconfigなし
        $maker = new \Maker\MoodleQuizXMLMaker("sample-N03.txt");
        $configs = $maker->getBeans();
        $this->assertEquals(2, count($configs));
        $this->assertEquals('cloze', $configs[0]->getConfig()->type);
        $this->assertEquals('初級 のデフォルト', $configs[0]->getConfig()->category);
        $this->assertEquals('問題名', $configs[0]->getConfig()->name);
        $this->assertEquals('お疲れさん', $configs[0]->getConfig()->commonFeedback);
        $this->assertEquals('はずれ', $configs[0]->getConfig()->ngFeedback);
        $this->assertEquals('合格', $configs[0]->getConfig()->okFeedback);
        $this->assertEquals('最初のはグラミーショーで敷かれるやつ', $configs[0]->getConfig()->hint[0]);
        $this->assertEquals('2番目のは木を切るもの', $configs[0]->getConfig()->hint[1]);
        $this->assertEquals('3番目のはメラニン色素がない生き物', $configs[0]->getConfig()->hint[2]);
        $this->assertTrue(is_array($configs[0]->getConfig()->hint));
        //Normal 4 shortanswer問題---2問目にはconfigなし
        $maker = new \Maker\MoodleQuizXMLMaker("sample-N04.txt");
        $configs = $maker->getBeans();
        $this->assertEquals(2, count($configs));
        $this->assertEquals('shortanswer', $configs[0]->getConfig()->type);
        $this->assertEquals('初級 のデフォルト', $configs[0]->getConfig()->category);
        $this->assertEquals('問', $configs[0]->getConfig()->name);
        $this->assertEquals('お疲れ', $configs[0]->getConfig()->commonFeedback);
        $this->assertEquals('はずれ', $configs[0]->getConfig()->ngFeedback);
        $this->assertEquals('合格', $configs[0]->getConfig()->okFeedback);
        $this->assertEquals('coffeeが綴りに入ります', $configs[0]->getConfig()->hint);

        //Normal 5 multichoice問題---2問目にはconfigあり
        $maker = new \Maker\MoodleQuizXMLMaker("sample-N05.txt");
        $configs = $maker->getBeans();
        $this->assertEquals(2, count($configs));
        $this->assertEquals('multichoice', $configs[0]->getConfig()->type);
        $this->assertEquals('初級 のデフォルト', $configs[0]->getConfig()->category);
        $this->assertEquals('問', $configs[0]->getConfig()->name);
        $this->assertEquals('お疲れ', $configs[0]->getConfig()->commonFeedback);
        $this->assertEquals('はずれ', $configs[0]->getConfig()->ngFeedback);
        $this->assertEquals('合格', $configs[0]->getConfig()->okFeedback);

        //Normal 5 multichoice問題---2問目にはconfigあり
        $this->assertEquals(2, count($configs));
        $this->assertEquals('multichoice', $configs[1]->getConfig()->type);
        $this->assertEquals('初級 のデフォルト', $configs[1]->getConfig()->category);
        $this->assertEquals('問', $configs[1]->getConfig()->name);
        $this->assertEquals('お疲れ', $configs[1]->getConfig()->commonFeedback);
        $this->assertEquals('これ知らなきゃダメでしょう!', $configs[1]->getConfig()->ngFeedback);
        $this->assertEquals('知ってて当然!', $configs[1]->getConfig()->okFeedback);
    }

    /**
     * @expectedException \Exception\ConfigException
     */
    public function testErrorConfig()
    {
        $maker = new \Maker\MoodleQuizXMLMaker("sample-E01.txt");
        $configs = $maker->getBeans();
    }

    /**
     * @expectedException \Exception\ConfigException
     */
    public function testErrorConfigEmpty()
    {
        $maker = new \Maker\MoodleQuizXMLMaker("sample-E02.txt");
        $configs = $maker->getBeans();
    }

    /**
     * @expectedException \Exception\ConfigException
     */
    public function testErrorConfigJsonBroken()
    {
        $maker = new \Maker\MoodleQuizXMLMaker("sample-E03.txt");
        $configs = $maker->getBeans();
    }

    /**
     * @expectedException \Exception\ConfigException
     */
    public function testErrorConfigNoType()
    {
        $maker = new \Maker\MoodleQuizXMLMaker("sample-E04.txt");
        $configs = $maker->getBeans();
    }

    /**
     * @expectedException \Exception\FileNotFoundException
     */
    public function testErrorFile()
    {
        $maker = new \Maker\MoodleQuizXMLMaker("NoExistFile.txt");
        $configs = $maker->getBeans();
    }

    /**
     * @expectedException \Exception\ConfigException
     */
    public function testErrorFirstLineHasNoType()
    {
        $maker = new \Maker\MoodleQuizXMLMaker("sample-E05.txt");
        $configs = $maker->getBeans();
    }
}