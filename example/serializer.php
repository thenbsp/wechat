<?php

require './example.php';

use Thenbsp\Wechat\Bridge\Serializer;

// array
$array = array(
    'name' => '冯特罗',
    'email' => 'thenbsp@gmail.com',
    'posotion' => 'Web Developer',
    'age' => 28,
    'gender' => 'M'
);

// object
$object = (object) $array;

// json
$jsonString = <<<EOF
{"name":"冯特罗","email":"thenbsp@gmail.com","posotion":"Web Developer","age":28,"gender":"M"}
EOF;

// xml
$xmlstring = <<<EOF
<person>
    <name>foo</name>
    <age>99</age>
    <sportsman>false</sportsman>
</person>
EOF;

// array to json
var_dump(Serializer::jsonEncode($array));

// array to xml
var_dump(Serializer::xmlEncode($array));

// json to array
var_dump(Serializer::jsonDecode($jsonString));

// xml to array
var_dump(Serializer::xmlEncode($xmlstring));

// object to json
$json = Serializer::jsonEncode($object);

var_dump($json);

// json to object
$object = Serializer::jsonDecode($json, array('json_decode_associative'=>false));

var_dump($object);
