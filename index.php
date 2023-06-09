<?php declare(strict_types=1);

require_once 'lib/Station.php';

$stations = Station::get_all();

require_once 'lib/stations.phtml';
