<?php

require_once "../beautify.php";

//$file = file_get_contents("example.md");

$string = <<<EOF
#Beautify
~~~ ruby
puts 'hello world'
~~~

sakai

~~~ javascript
$('#result').html('waiting...');

var promise = wait();
promise.done(result);

function result() {
  $('#result').html('done');
}

function wait() {
  var deferred = $.Deferred();

  setTimeout(function() {
    deferred.resolve();
  }, 2000);

  return deferred.promise();
}
~~~


| header 1 | header 2 |
| -------- | -------- |
| cell 1   | cell 2   |
| cell 3   | cell 4   |
EOF;




$beautifiedFile = beautify($string);

echo $beautifiedFile;

?>
