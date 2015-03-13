<?php
/**
 * Created by PhpStorm.
 * User: Sol
 * Date: 23.12.2014
 * Time: 16:47
 */


function getLanguages()
{
  $request = <<<XML
<?xml version="1.0" encoding="ISO-8859-1" ?>
<ETLanguageListRQ>
<Client>TESTXMLB2C</Client>
</ETLanguageListRQ>
XML;

  $postData = http_build_query(
    array(
      'request' => $request
    ));

  $opts = array('http' =>
    array(
      'method' => 'POST',
      'header' => 'Content-type: application/x-www-form-urlencoded',
      'content' => $postData
    )
  );

  $context = stream_context_create($opts);

  $result = file_get_contents('https://wwwet.eurotours.at/xml/LanguageWebRequest', false, $context);
  $xml = new SimpleXMLElement($result);

  for ($i = 0, $size = count($xml->LanguageList[0]->Language); $i < $size; $i++) {
    $langs[] = (string)$xml->LanguageList[0]->Language[$i]->LanguageCode;
  }

  return $langs;

}

function getAreas($params)
{

  $language = $params->language;
  $request = <<<XML
<?xml version="1.0" encoding="ISO-8859-1" ?>
<ETAreaListRQ>
<Client>TESTXMLB2B</Client>
<LanguageCode>$language</LanguageCode>
</ETAreaListRQ>
XML;
  $requestXml = new SimpleXMLElement($request);
  $requestXmlTxt=$requestXml->asXML();
  $postData = http_build_query(
    array(
      'request' => $request
    ));

  $opts = array('http' =>
    array(
      'method' => 'POST',
      'header' => 'Content-type: application/x-www-form-urlencoded',
      'content' => $postData
    )
  );

  $context = stream_context_create($opts);

  $result = file_get_contents('https://wwwet.eurotours.at/xml/AreaWebRequest', false, $context);
  $xml = new SimpleXMLElement($result);

//    for($i = 0, $size = count($xml->LanguageList[0]->Language); $i < $size; $i++) {
//        $langs[]=(string)$xml->LanguageList[0]->Language[$i]->LanguageCode;
//    }
  return $xml->AreaList;

}

function getLocations($params)
{
  $language = $params->language;
  $area= $params->area->AreaCode;
  $request = <<<XML
<?xml version="1.0" encoding="ISO-8859-1" ?>
<ETLocationListRQ>
<Client>TESTXMLB2B</Client>
<LanguageCode>$language</LanguageCode>
<AreaCode>$area</AreaCode>
</ETLocationListRQ>
XML;

  $postData = http_build_query(
    array(
      'request' => $request
    ));

  $opts = array('http' =>
    array(
      'method' => 'POST',
      'header' => 'Content-type: application/x-www-form-urlencoded',
      'content' => $postData
    )
  );

  $context = stream_context_create($opts);

  $result = file_get_contents('https://wwwet.eurotours.at/xml/LocationWebRequest', false, $context);
  $xml = new SimpleXMLElement($result);

  return $xml->LocationList;

}

$type = $_GET['type'];
$params = json_decode($_GET['params']);


switch ($type) {
  case "langs":
    $arr = getLanguages();
    break;
  case "areas":
    $arr = getAreas($params);
    break;
  case "locations":
    $arr = getLocations($params);
    break;
}

echo json_encode($arr); // {"a":1,"b":2,"c":3,"d":4,"e":5}
//echo $type.$type;