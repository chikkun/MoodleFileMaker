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
        $this->assertEquals('F', $configs[0]->getAnswer());
        //2問目
        $this->assertEquals('truefalse', $configs[1]->getConfig()->type);
        $this->assertEquals('初級 のデフォルト', $configs[1]->getConfig()->category);
        $this->assertEquals('問', $configs[1]->getConfig()->name);
        $this->assertEquals('お疲れ', $configs[1]->getConfig()->commonFeedback);
        $this->assertEquals('はずれ', $configs[1]->getConfig()->ngFeedback);
        $this->assertEquals('Java Teaはとても美味しい。', $configs[1]->getQuestion());
        $this->assertEquals('T', $configs[1]->getAnswer());

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
        $this->assertEquals('CoffeeScriptは飲むととても美味しい。', $configs[0]->getQuestion());
        $this->assertEquals('F', $configs[0]->getAnswer());

        //2問目
        $this->assertEquals('truefalse', $configs[1]->getConfig()->type);
        $this->assertEquals('問', $configs[1]->getConfig()->name);
        $this->assertEquals('初級 のデフォルト', $configs[1]->getConfig()->category);
        $this->assertEquals('ご苦労!', $configs[1]->getConfig()->commonFeedback);
        $this->assertEquals('はずれ', $configs[1]->getConfig()->ngFeedback);
        $this->assertEquals('合格', $configs[1]->getConfig()->okFeedback);
        $this->assertEquals('Java Teaはとても美味しい。', $configs[1]->getQuestion());
        $this->assertEquals('T', $configs[1]->getAnswer());

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

        $this->assertEquals('cloze', $configs[1]->getConfig()->type);
        $this->assertEquals('初級 のデフォルト', $configs[1]->getConfig()->category);
        $this->assertEquals('問題名', $configs[1]->getConfig()->name);
        $this->assertEquals('お疲れさん', $configs[1]->getConfig()->commonFeedback);
        $this->assertEquals('はずれ', $configs[1]->getConfig()->ngFeedback);
        $this->assertEquals('合格', $configs[1]->getConfig()->okFeedback);
        $this->assertEquals('最初のはグラミーショーで敷かれるやつ', $configs[1]->getConfig()->hint[0]);
        $this->assertEquals('2番目のは木を切るもの', $configs[1]->getConfig()->hint[1]);
        $this->assertEquals('3番目のはメラニン色素がない生き物', $configs[1]->getConfig()->hint[2]);
        $this->assertTrue(is_array($configs[1]->getConfig()->hint));
        $text =<<<'EOF'
    # -*- encoding: utf-8 -*-
    require '((redcarpet))'
    require '((nokogiri|kanna|kanaduchi))'
    require '((albino|wrong#だめっしょ1|wrong2#ダメっしょ2))'
    require 'CodeRay'
    class CodeRayify < Redcarpet::Render::HTML
      def block_code(code, language)
        CodeRay.scan(code, language).div
      end
    end
    def markdown(text)
      coderayified = CodeRayify.new(:filter_html => true,
                                    :hard_wrap => true)
      options = {
        :fenced_code_blocks => true,
        :no_intra_emphasis => true,
        :autolink => true,
        :strikethrough => true,
        :lax_html_blocks => true,
        :superscript => true,
        :tables => true
      }
      markdown_to_html = Redcarpet::Markdown.new(coderayified, options)
      markdown_to_html.render(text)
    end
    string = <<"END"
    ~~~ javascript
    puts 'hello world'
    | header 1 | header 2 |
    | -------- | -------- |
    | cell 1   | cell 2   |
    | cell 3   | cell 4   |
    END
    print markdown(string)
EOF;
        $this->assertEquals($text, $configs[0]->getQuestion());
        $this->assertEmpty($configs[0]->getAnswer());
        $text =<<< 'EOF'
    use strict;
    use warnings;
    use Markdent::Simple::Document;
    my $parser = Markdent::Simple::Document->new;
    print $parser->markdown_to_html(
    title => 'GFM Example',
    dialect => 'GitHub',
    markdown => do { local $/; <DATA> },
    );
    __DATA__
    # GitHub Flavored Markdown
    ## Syntax highlighting
    ```perl
    use strict;
    use warnings;
    use 5.016;
    say 'Hello!';
    ```
EOF;
        $this->assertEquals($text, $configs[1]->getQuestion());
        $this->assertEmpty($configs[1]->getAnswer());



        //Normal 4 shortanswer問題---2問目にはconfigあり
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
        $this->assertEquals('飲むととても美味しい飲み物は何ですか？', $configs[0]->getQuestion());
        $this->assertEquals('CoffeeScript', $configs[0]->getAnswer());

        $this->assertEquals('shortanswer', $configs[1]->getConfig()->type);
        $this->assertEquals('初級 のデフォルト', $configs[1]->getConfig()->category);
        $this->assertEquals('問', $configs[1]->getConfig()->name);
        $this->assertEquals('お疲れ', $configs[1]->getConfig()->commonFeedback);
        $this->assertEquals('これ知らなきゃダメでしょう!', $configs[1]->getConfig()->ngFeedback);
        $this->assertEquals('当然', $configs[1]->getConfig()->okFeedback);
        $this->assertEquals('Jで始まる言語です', $configs[1]->getConfig()->hint);
        $this->assertEquals('淹れるとおいしい飲み物は何ですか？', $configs[1]->getQuestion());
        $this->assertEquals('Java', $configs[1]->getAnswer());

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
        $this->assertEquals('飲むととても美味しい飲み物は何ですか？', $configs[0]->getQuestion());
        $this->assertEquals('CoffeeScript|PHP|Ruby|Python', $configs[0]->getAnswer());

        //Normal 5 multichoice問題---2問目にはconfigあり
        $this->assertEquals(2, count($configs));
        $this->assertEquals('multichoice', $configs[1]->getConfig()->type);
        $this->assertEquals('初級 のデフォルト', $configs[1]->getConfig()->category);
        $this->assertEquals('問', $configs[1]->getConfig()->name);
        $this->assertEquals('お疲れ', $configs[1]->getConfig()->commonFeedback);
        $this->assertEquals('これ知らなきゃダメでしょう!', $configs[1]->getConfig()->ngFeedback);
        $this->assertEquals('知ってて当然!', $configs[1]->getConfig()->okFeedback);
        $this->assertEquals('淹れるとおいしい飲み物は何ですか？', $configs[1]->getQuestion());
        $this->assertEquals('Java|PHP|Python|Ruby', $configs[1]->getAnswer());
    }
    public function test10Questions()
    {
        $maker = new \Maker\MoodleQuizXMLMaker("sample-N06.txt");
        $configs = $maker->getBeans();
        //1問目
        $this->assertEquals(10, count($configs));
        $this->assertEquals('truefalse', $configs[0]->getConfig()->type);
        $this->assertEquals('PHP', $configs[0]->getConfig()->category);
        $this->assertEquals('問', $configs[0]->getConfig()->name);
        $this->assertEquals('お疲れ', $configs[0]->getConfig()->commonFeedback);
        $this->assertEquals('PHPにはifやforのブロックスコープがありません。', $configs[0]->getConfig()->ngFeedback);
        $this->assertEquals('合格', $configs[0]->getConfig()->okFeedback);
        $text =<<<'EOF'
PHPにおいて、if文の中で使った変数は外でも参照できる(下のechoで「inside if」が出力される)。
<?php
    if(true) {
        $flag = "inside if";
    }
    echo $flag."\n";
?>
EOF;
        $this->assertEquals($text, $configs[0]->getQuestion());
        $this->assertEquals('t', $configs[0]->getAnswer());

        //2問目
        $this->assertEquals('truefalse', $configs[1]->getConfig()->type);
        $this->assertEquals('PHP', $configs[1]->getConfig()->category);
        $this->assertEquals('問', $configs[1]->getConfig()->name);
        $this->assertEquals('お疲れ', $configs[1]->getConfig()->commonFeedback);
        $this->assertEquals('PHPの「==」は型を無視して比較します。「===」は型も同じ時のみtrueになります。', $configs[1]->getConfig()->ngFeedback);
        $this->assertEquals('合格', $configs[1]->getConfig()->okFeedback);
        $text =<<<'EOF'
下のコードを実行すると、「false」が出力される。
<?php
    $foo = "0";
    if ($foo == 0) {
        echo "true";
    } else {
        echo "false";
    }
?>
EOF;
        $this->assertEquals($text, $configs[1]->getQuestion());
        $this->assertEquals('F', $configs[1]->getAnswer());

        //3問目
        $this->assertEquals('truefalse', $configs[2]->getConfig()->type);
        $this->assertEquals('PHP', $configs[2]->getConfig()->category);
        $this->assertEquals('問', $configs[2]->getConfig()->name);
        $this->assertEquals('お疲れ', $configs[2]->getConfig()->commonFeedback);
        $this->assertEquals('PHPでは整数値を文字列と比較する際、文字列が数値に変換されますが、有効な数字データから始まらない場合は0となります。', $configs[2]->getConfig()->ngFeedback);
        $this->assertEquals('合格', $configs[2]->getConfig()->okFeedback);
        $text =<<<'EOF'
下のコードを実行すると、「true」が出力される。
<?php
    $foo = "test";
    if ($foo == 0) {
        echo "true";
    } else {
        echo "false";
    }
?>
EOF;
        $this->assertEquals($text, $configs[2]->getQuestion());
        $this->assertEquals('T', $configs[2]->getAnswer());

        //4問目
        $this->assertEquals('truefalse', $configs[3]->getConfig()->type);
        $this->assertEquals('PHP', $configs[3]->getConfig()->category);
        $this->assertEquals('問', $configs[3]->getConfig()->name);
        $this->assertEquals('お疲れ', $configs[3]->getConfig()->commonFeedback);
        $this->assertEquals('前問同様、文字列を数字で評価して0なので、trueにまります。', $configs[3]->getConfig()->ngFeedback);
        $this->assertEquals('成長しましたねｗ', $configs[3]->getConfig()->okFeedback);
        $text =<<<'EOF'
配列にその値が存在するかどうかを判定する関数「in_array」を使った下のコードを実行すると、「true」が出力される。
<?php
    $str_array = array('a', 'b', 'c');
    if (in_array(0, $str_array)) {
        echo "true";
    } else {
        echo "false";
    }
?>
EOF;
        $this->assertEquals($text, $configs[3]->getQuestion());
        $this->assertEquals('t', $configs[3]->getAnswer());

        //5問目
        $this->assertEquals('truefalse', $configs[4]->getConfig()->type);
        $this->assertEquals('PHP', $configs[4]->getConfig()->category);
        $this->assertEquals('問', $configs[4]->getConfig()->name);
        $this->assertEquals('お疲れ', $configs[4]->getConfig()->commonFeedback);
        $this->assertEquals('前問同様、colorが0に変換され、$noodles[\'udon\'][0]になり、文字列whiteの最初の文字が出力されます(warningが出ますが)。', $configs[4]->getConfig()->ngFeedback);
        $this->assertEquals('成長しましたねｗ', $configs[4]->getConfig()->okFeedback);
        $text =<<<'EOF'
下のコードを実行すると、nullが表示されます。
<?php
    $noodles = array(
        'udon' => 'white',
        'pasta' => 'yellow',
        'malony' => null,
    );
    echo $noodles['udon']['color'] . "\n";
?>
EOF;
        $this->assertEquals($text, $configs[4]->getQuestion());
        $this->assertEquals('f', $configs[4]->getAnswer());

        //6問目
        $this->assertEquals('truefalse', $configs[5]->getConfig()->type);
        $this->assertEquals('PHP', $configs[5]->getConfig()->category);
        $this->assertEquals('問', $configs[5]->getConfig()->name);
        $this->assertEquals('お疲れ', $configs[5]->getConfig()->commonFeedback);
        $this->assertEquals('連想配列のキーの存在確認にはissetは使えません(→array_key_exists)。', $configs[5]->getConfig()->ngFeedback);
        $this->assertEquals('成長しましたねｗ', $configs[5]->getConfig()->okFeedback);
        $text =<<<'EOF'
下のコードを実行すると、trueが表示されます。
<?php
    $noodles = array(
        'udon' => 'white',
        'pasta' => 'yellow',
        'malony' => null,
    );
    if (isset($noodles['malony'])) {
        echo "true\n";
    } else {
        echo "false\n";
    }
?>
EOF;
        $this->assertEquals($text, $configs[5]->getQuestion());
        $this->assertEquals('FaLse', $configs[5]->getAnswer());

        //7問目
        $this->assertEquals('truefalse', $configs[6]->getConfig()->type);
        $this->assertEquals('PHP', $configs[6]->getConfig()->category);
        $this->assertEquals('問', $configs[6]->getConfig()->name);
        $this->assertEquals('お疲れ', $configs[6]->getConfig()->commonFeedback);
        $this->assertEquals('文字列や数値、配列はリファレンス渡しではないので、書き換えた結果が反映されません。', $configs[6]->getConfig()->ngFeedback);
        $this->assertEquals('成長しましたねｗ', $configs[6]->getConfig()->okFeedback);
        $text =<<<'EOF'
下のコードを実行すると、「2」が出力される。
<?php
   function test($array){
      $array[0] = 2;
   }
   $array[0] = 1;
   test($array);
   echo $array[0];
?>
EOF;
        $this->assertEquals($text, $configs[6]->getQuestion());
        $this->assertEquals('F', $configs[6]->getAnswer());

        //8問目
        $this->assertEquals('truefalse', $configs[7]->getConfig()->type);
        $this->assertEquals('PHP', $configs[7]->getConfig()->category);
        $this->assertEquals('問', $configs[7]->getConfig()->name);
        $this->assertEquals('お疲れ', $configs[7]->getConfig()->commonFeedback);
        $this->assertEquals('文字列や数値、配列はリファレンス渡しではないので、書き換えた結果が反映されません。', $configs[7]->getConfig()->ngFeedback);
        $this->assertEquals('成長しましたねｗ', $configs[7]->getConfig()->okFeedback);
        $text =<<<'EOF'
下のコードを実行すると、「bowbow」が出力される。
<?php
    class Hoge{
        public $dog = "wanwan";
        public $cat = "nyan";
    }
   function test($obj){
      $obj->dog = "bowbow";
   }
   $h = new Hoge();
   test($h);
   echo $h->dog;
?>
EOF;
        $this->assertEquals($text, $configs[7]->getQuestion());
        $this->assertEquals('True', $configs[7]->getAnswer());

        //9問目
        $this->assertEquals('truefalse', $configs[8]->getConfig()->type);
        $this->assertEquals('PHP', $configs[8]->getConfig()->category);
        $this->assertEquals('問', $configs[8]->getConfig()->name);
        $this->assertEquals('お疲れ', $configs[8]->getConfig()->commonFeedback);
        $this->assertEquals('PHPは、連想配列でも添え字以外に別途インデックスを持っていて、格納した順を覚えています。', $configs[8]->getConfig()->ngFeedback);
        $this->assertEquals('成長しましたねｗ', $configs[8]->getConfig()->okFeedback);
        $text =<<<'EOF'
PHPの連想配列は順番が保証されている。例えば、次のコードを実行すると、「key1:val1→key2:val2→key3:val3」の順番で出力される。
<?php
    $hash['key1'] = 'val1';
    $hash['key2'] = 'val2';
    $hash['key3'] = 'val3';
    foreach($h as $key => $value){
        print $key . ":" . $value . "\n";
    }
?>
EOF;
        $this->assertEquals($text, $configs[8]->getQuestion());
        $this->assertEquals('TRUE', $configs[8]->getAnswer());

        //10問目
        $this->assertEquals('truefalse', $configs[9]->getConfig()->type);
        $this->assertEquals('PHP', $configs[9]->getConfig()->category);
        $this->assertEquals('問', $configs[9]->getConfig()->name);
        $this->assertEquals('お疲れ', $configs[9]->getConfig()->commonFeedback);
        $this->assertEquals('PHPは、添え字が数字の配列でも、結局全く前問の連想配列と同じ扱いで、入れた順を覚えています。', $configs[9]->getConfig()->ngFeedback);
        $this->assertEquals('成長しましたねｗ', $configs[9]->getConfig()->okFeedback);
        $text =<<<'EOF'
例えば、次のコードを実行すると、「0:え→1:う→2:い→3:あ」の順番で出力される。
<?php
    $array[3] = 'あ';
    $array[2] = 'い';
    $array[1] = 'う';
    $array[0] = 'え';
        foreach($array as $key => $value){
           print $key . ":" . $value . "\n";
        }
?>
EOF;
        $this->assertEquals($text, $configs[9]->getQuestion());
        $this->assertEquals('FALSE', $configs[9]->getAnswer());

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