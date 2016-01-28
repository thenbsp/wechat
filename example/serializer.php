<?php

require './example.php';

$serializer = new Thenbsp\Wechat\Bridge\Serializer;

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
var_dump($serializer->jsonEncode($array));

// array to xml
var_dump($serializer->xmlEncode($array));

// json to array
var_dump($serializer->jsonDecode($jsonString));

// xml to array
var_dump($serializer->xmlEncode($xmlstring));

// object to json
$json = $serializer->jsonEncode($object);

var_dump($json);

// json to object
$object = $serializer->jsonDecode($json, array('json_decode_associative'=>false));

var_dump($object);
