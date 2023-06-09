<?php declare(strict_types=1);

require_once 'Model.php';
require_once 'Run.php';

class Station implements Model
{
    public $id;
    public $name;
    public $lift_count;
    public $snow;
    public $light;

    public $rating;

    public $runs = [];

    public function __construct($id, $name, $lift_count, $snow, $light)
    {
        $this->id = $id;
        $this->name = $name;
        $this->lift_count = $lift_count;
        $this->snow = $snow;
        $this->light = $light;
    }

    public static function get_all(): array
    {
        $mysqli = @new mysqli('localhost', 'root', '', 'stacje_narciarskie');
        if ($mysqli->connect_errno) die('Brak połączenia z MySQL');
        $mysqli->query('SET NAMES UTF8');
        $rs = $mysqli->query('SELECT * FROM stacje');
        $stations = [];
        $i = 1;
        while($rec = $rs->fetch_assoc())
        {
            $station = Station::get_by_id($rec['id_stacji']);
            $stations[] = $station;
            $i++;
        }
        $mysqli->close();
        return $stations;
    }

    public static function get_by_id($id): Station
    {
        // TODO: Implement get_by_id() method.
        $mysqli = @new mysqli('localhost', 'root', '', 'stacje_narciarskie');
        if ($mysqli->connect_errno) die('Brak połączenia z MySQL');
        $mysqli->query('SET NAMES UTF8');
        $rs = $mysqli->query('SELECT * FROM stacje WHERE id_stacji='.$id);
        $rec = $rs->fetch_assoc();
        $values = [
            "id" => $rec['id_stacji'],
            "name" => $rec['nazwa'],
            "lift_count" => $rec['orczyki'] + $rec['krzeselka'] + $rec['gondole/kolejki'],
            "snow" => $rec['nasniezanie'],
            "light" => $rec['oswietlenie']
        ];
        $station = new Station($values['id'], $values['name'], $values['lift_count'], $values['snow'], $values['light']);
        $station->rating = $station->get_rating();
        $station->runs = $station->get_runs();
        $mysqli->close();
        return $station;
    }

    private function get_rating()
    {
        $mysqli = @new mysqli('localhost', 'root', '', 'stacje_narciarskie');
        if ($mysqli->connect_errno) die('Brak połączenia z MySQL');
        $mysqli->query('SET NAMES UTF8');
        $rs = $mysqli->query('SELECT AVG(ocena) AS srednia FROM oceny WHERE stacja='.$this->id);
        $rec = $rs->fetch_assoc();
        $mysqli->close();
        return round($rec['srednia'], 2);
    }

    private function get_runs(): array
    {
        $mysqli = @new mysqli('localhost', 'root', '', 'stacje_narciarskie');
        if ($mysqli->connect_errno) die('Brak połączenia z MySQL');
        $mysqli->query('SET NAMES UTF8');
        $rs = $mysqli->query('SELECT * FROM trasy WHERE id_stacji='.$this->id);
        $runs = [];
        while($rec = $rs->fetch_assoc())
        {
            $run = new Run($rec['id_trasy'], $rec['nazwa'], $rec['dlugosc']);
            $runs[] = $run;
        }
        $mysqli->close();
        return $runs;
    }

    public function rate($rating): void
    {
        $mysqli = @new mysqli('localhost', 'root', '', 'stacje_narciarskie');
        if ($mysqli->connect_errno) die('Brak połączenia z MySQL');
        $mysqli->query('SET NAMES UTF8');
        $mysqli->query('INSERT INTO oceny (stacja, ocena) VALUES ('.$this->id.', '.$rating.')');
        $mysqli->close();
    }
}