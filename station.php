<?php declare(strict_types=1);

require_once 'lib/Station.php';

$id = $_GET['id'];

if(!isset($id)) {
    header('Location: index.php');
    exit();
}

$station = Station::get_by_id($id);

require_once 'lib/station.phtml';
