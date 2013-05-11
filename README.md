# MoodleFileMaker
他製品のFileMakerとは無関係です。基本的にはMoodleの

1. 正誤問題(truefalse)
2. 選択問題(multichoice) --- 複数回答も可
3. ちょっと複雑な穴埋めや選択問題(cloze)
4. 記述問題(shortanswer)
5. 記述・説明(description)

の5つの問題形式に応じた(上記5は厳密に言えば問題じゃないですが)、あるルール(後述)に則ったテキストファイルから、MoodleのXML形式のファイルを作成するものです。

## インストール

1. PHPがインストールされていることは前提です(namespaceを使っているので、5.3以上必須)。また、コマンドラインのアプリなので、出来ればphpコマンドにパスが通っていた方が良いでしょう。

2. gitで本アプリケーションをcloneする。
```bash
git clone https://github.com/chikkun/MoodleFileMaker
```
3. 2でcloneしたディレクトリを「%MoodleFileMaker%」とすると、
```bash
git clone https://github.com/edwinm/Beautify
```
してできたBeautifyディレクトリーごと%MoodleFileMaker%の直下にコピーします。

4. 次に http://logging.apache.org/log4php/download.html からlog4phpをダウンロードし、これを解凍します。2013年5月時点で最新のものがapache-log4php-2.3.0-src.zipなので、このバージョンならば次のようなディレクトリー構造になっているはずです。
```
apache-log4php-2.3.0
├── apidocs
│   └── resources
├── site
│   ├── apidocs
│   │   └── resources
│   ├── coverage-report
│   │   ├── css
│   │   ├── img
│   │   └── js
│   ├── css
│   ├── docs
│   │   ├── appenders
│   │   └── layouts
│   ├── images
│   │   └── logos
│   ├── img
│   └── js
└── src
    ├── assembly
    ├── changes
    ├── examples
    │   ├── php
    │   └── resources
    ├── main
    │   └── php
    │       ├── appenders
    │       ├── configurators
    │       ├── filters
    │       ├── helpers
    │       ├── layouts
    │       ├── pattern
    │       ├── renderers
    │       └── xml
    ├── site
    │   ├── apt
    │   ├── resources
    │   │   ├── css
    │   │   ├── images
    │   │   │   └── logos
    │   │   ├── img
    │   │   └── js
    │   └── xdoc
    │       └── docs
    │           ├── appenders
    │           └── layouts
    └── test
        ├── config
        ├── php
        │   ├── appenders
        │   ├── configurators
        │   ├── filters
        │   ├── helpers
        │   ├── layouts
        │   ├── pattern
        │   └── renderers
        └── resources
            └── configs
                ├── adapters
                │   ├── ini
                │   ├── php
                │   └── xml
                ├── appenders
                ├── loggers
                └── renderers
```
この「apache-log4php-2.3.0/src/main/php」というディレクトリーをlog4phpにリネームして、そのディレクトリーを%MoodleFileMaker%にコピーします。
5. Zend Frameworkを http://framework.zend.com/downloads/latest からダウンロードし、解凍します。ただし、基本的にはAutoloader機能を使っているだけですので、解凍して出来た「Zend/Loader」ディレクトリだけで十分です。いずれにしろ、Zendディレクトリを%MoodleFileMaker%にコピーします。
6. binディレクトリにパスを通しておくと便利かもしれません。

## ファイルのざっくりとした構成
```
-----------------
config行
問題文
answer:答え

config行(省略可能)
問題文
ans:答え
-----------------
```
というように、空行が問題の区切りになります(「-----------------」はいりません。わかりやすいようにしただけです)。なので、説明文(問題がないdescription)のような場合、どうしても空行が必要になったりします。そのような場合は、行頭にスペースを入れて、見かけは空行だけれども実際にはスペースがある(1つ以上)という行にして下さい。**答えの行は必ず(ans:|answer:)から書き始めなくてはなりません。**ただし、cloze問題だけは問題文内に答えがあるので、この答えの行は出てきません。

## Github Flavored Markdown<a name="gfm">
詳しくは https://help.github.com/articles/github-flavored-markdown を見て下さい。
基本的には、Markdownなのですが、

* 改行の度に段落になります。
* URLを自動でリンクにします。
* 行頭の「\`\`\`」〜行頭「\`\`\`」の間にコードが書けます。コードの種類を指定すると、その言語にしたがって色づけをしてくれます(下の例はの「｀」は全角になっていますが、実際には半角です)。

```ruby
    ｀｀｀ruby
    require 'redcarpet'
    markdown = Redcarpet.new("Hello World!")
    puts markdown.to_html
    ｀｀｀
```

## config行
### 全てに共通したもの
問題形式により必要なものが若干違うので、ここでは共通の考え方を記します。

1. 設定は最低1行目には必要(1問目のconfigとして記述)。また、**ここのtypeは省略できません(問題形式がわからないと何も出来ないので)。**
2. 書き方はJSON形式で記入します(ちょっと面倒ですが、複数のヒントなどを扱うことが出来るようにするために、JSONを採用しています)。以下がその例です。
```json
config:{"type":"TorF", "category":"初級 のデフォルト", "name":"問", "commonFeedback":"お疲れ", "ngFeedback":"はずれ", "okFeedback":"合格"}
```
3. 2問目以降のconfig行は**省略可能**で、その場合、前のconfigの値をそのまま使います。
4. 前の設定を書き換えたい場合は、書き換えたい項目のみ書くだけでOKです。例えば、次の例では、commonFeedbackが1問目では「お疲れ」ですが、2問目では他の設定はそのままで、commonFeedbackのみ2問目では「ご苦労！」に変更されます。
```json
config:{"type":"TorF", "category":"初級 のデフォルト", "name":"問", "commonFeedback":"お疲れ", "ngFeedback":"はずれ", "okFeedback":"合格"}
CoffeeScriptは飲むととても美味しい。
ans:F
　　
config:{"commonFeedback":"ご苦労!"}
Java Teaはとても美味しい。
ans:T
```
1. 設定項目
   1. type:問題形式を指定します(大文字・小文字は区別しません)。
      * 正誤問題→TorF/truefalse のいずれか
      * 記述問題→sans/shortanswer のいずれか
      * 選択問題→mchoice/multichoice のいずれか
      * 穴埋め問題→anaume/cloze のいずれか
      * 解説→desc/description のいずれか

      これらのどれにも当てはまらない場合は、例外が投げられます。
   2. category:これは指定しないと、デフォルトの「$system$/システム のデフォルト」が利用されます。
   3. name:問題名。デフォルトは「問」です。
   4. commonFeedback:これは常に表示されるフィードバックです。デフォルトは空文字です。
   5. ngFeedback:問題に不正解になった場合のフィードバックです。デフォルトは空文字です。
   6. okFeedback:問題が正解だった場合のフィードバックです。デフォルトは空文字です。

## 正誤問題の書き方
正誤問題を記述するには次のことに留意して下さい。

1. configのtypeに(TorF|truefalse)のいずれかを指示する(大文字小文字は区別なし)。
2. okFeedbackは正解時のフィードバックで、ngFeedbackは間違えたときのフィードバックとなります。答えがTrueやFalseに関わらず、合っているか間違っているかに依存します。後述するように、解答内でこのフィードバック内容を上書きできます。
3. 問題文を書きます。問題文は何行でもかけます。[Github Flavored Markdown](#gfm)のルールにしたがってHTMLに変換されます。
4. 答えは行頭は行頭から(ans:|answer:)から書き始め(大文字小文字区別せず)、この後に答えを書きます。答えに許されているものは、大文字小文字を区別せず(T|true)か(F|false)のいずれかです。
5. 答えの後に「#」の後に間違った場合のフィードバックと正解した場合のフィードバックを入れることが可能です。例えば、
```
Answer:true#間違った際のフィードバック#正解だった際のフィードバック
```
のように書き、間違い→正解という順番に書きます。**正解の方は省略可能ですが、間違いを省略して、正解だけというのはできません。その場合は**
```
Answer:true##正解だった際のフィードバック
```
というように書いて下さい。
