<?php

// Configuration
$importFile = '/whiterabbit.baart.ch/import/favourites.gpx';
$wptType = ['white rabbit'];

// read the OsmAnd favourites
$gpx = simplexml_load_file(dirname(__DIR__) . $importFile);

echo ('<pre>');

// new xml object
$newXML = new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8' standalone='yes' ?><gpx></gpx>");

function cmp($a, $b)
{
  if ($a == $b) {
    return 0;
  }
  return strcmp(($a['year'] . $a['month'] . $a['day']), ($b['year'] . $b['month'] . $b['day']));
}

// first put the necessary info into an array
// only an array can be sorted later
$tmpArr = array();
foreach ($gpx->wpt as $pt) {
  // only take wanted types of waypoints
  if (in_array($pt->type, $wptType)) {
    $tmpArr[] = [
      'lat' => (string) $pt['lat'],
      'lon' => (string) $pt['lon'],
      'name' => (string) htmlspecialchars($pt->name),
      'type' => (string) htmlspecialchars($pt->type),
      'day' => (string) substr($pt->name, 0, 2),
      'month' => (string) substr($pt->name, 3, 2),
      'year' => (string) substr($pt->name, 6, 2)
    ];
  }
}
usort($tmpArr, "cmp");
//var_dump($tmpArr);


foreach ($tmpArr as $pt) {
  $wpt = $newXML->addChild('wpt');
  $wpt->addAttribute('lat', (string) $pt['lat']);
  $wpt->addAttribute('lon', (string) $pt['lon']);
  $wpt->addChild('name', $pt['name']);
  $wpt->addChild('type', $pt['type']);
  $wpt->addChild('day', $pt['day']);
  $wpt->addChild('month', $pt['month']);
  $wpt->addChild('year', $pt['year']);
  print((string) $pt['name'] . '<br>');
}

var_dump($newXML->asXML('waypoints.gpx'));
