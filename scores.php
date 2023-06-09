<?php declare(strict_types=1);

require_once 'lib/Station.php';

$stations = Station::get_all();

usort($stations, function($a, $b) {
    return $b->rating <=> $a->rating;
});

$stations = array_filter($stations, function($station) {
    return $station->rating > 0;
});

$stations = array_slice($stations, 0, 3);

require_once 'lib/scores.phtml';
