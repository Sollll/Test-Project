<?php
$soap = curl_init("http://www.webservicex.net/geoipservice.asmx");
curl_setopt($soap, CURLOPT_POST, 1);
curl_setopt($soap, CURLOPT_RETURNTRANSFER, 1);

$request = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Envelope xmlns="http://www.w3.org/2003/05/soap-envelope">
    <Body>
        <ns1:GetGeoIP xmlns:ns1="http://www.webservicex.net/">
            <ns1:IPAddress>192.168.0.1</ns1:IPAddress>
        </ns1:GetGeoIP>
    </Body>
</Envelope>
XML;

curl_setopt($soap, CURLOPT_HTTPHEADER,
    array('Content-Type: text/xml; charset=utf-8',
        'Content-Length: '.strlen($request)));

curl_setopt($soap, CURLOPT_POSTFIELDS, $request);
$response = curl_exec($soap);
curl_close($soap);
