<?php

// Configuration
$importFile = '/v3/import/favourites.gpx';
$wptType = ['white rabbit'];

// read the OsmAnd favourites
$gpx = simplexml_load_file(dirname(__DIR__) . $importFile);

// new xml object
$newXML = new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8' standalone='yes' ?><gpx></gpx>");

foreach ($gpx->wpt as $pt) {
  if (in_array($pt->type, $wptType)) {

    $wpt = $newXML->addChild('wpt');
    $wpt->addAttribute('lat', (string) $pt['lat']);
    $wpt->addAttribute('lon', (string) $pt['lon']);
    $wpt->addChild('name', (string) htmlspecialchars($pt->name));
    $wpt->addChild('type', (string) $pt->type);
    print((string) $pt->name . '<br>');
  }
}

echo ('<pre>');
var_dump($newXML->asXML('waypoints.gpx'));
