<?php declare(strict_types=1);

if(!isset($_GET['station']) || !isset($_GET['rating'])) {
    header('Location: index.php');
    exit();
}

require_once 'lib/Station.php';

$station = Station::get_by_id($_GET['station']);
$rating = $_GET['rating'];

$station->rate($rating);

header('Location: scores.php');
