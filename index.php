<?php

print("Start");

$request = <<<XML
<?xml version="1.0" encoding="ISO-8859-1" ?>
<ETLanguageListRQ>
<Client>TESTXMLB2C</Client>
</ETLanguageListRQ>
XML;


$postdata = http_build_query(
  array(
    'request' => $request
  )
);

$opts = array('http' =>
  array(
    'method' => 'POST',
    'header' => 'Content-type: application/x-www-form-urlencoded',
    'content' => $postdata
  )
);

$context = stream_context_create($opts);

$result = file_get_contents('https://wwwet.eurotours.at/xml/LanguageWebRequest', false, $context);
$xml = new SimpleXMLElement($result);
$k = 9;
$langs = array();
for ($i = 0, $size = count($xml->LanguageList[0]->Language); $i < $size; $i++) {
  $langs[] = (string)$xml->LanguageList[0]->Language[$i]->LanguageCode;
}
$k = 9;
?>

<br>
Some changes.