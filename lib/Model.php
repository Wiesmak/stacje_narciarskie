<?php declare(strict_types=1);

interface Model
{
    public static function get_all();
    public static function get_by_id($id);
}