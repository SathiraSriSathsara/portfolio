<?php
declare(strict_types=1);
namespace App\Services;
final class Slugger { public static function make(string $value): string {$value=iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$value)?:$value;$value=strtolower(trim($value));$value=preg_replace('/[^a-z0-9]+/','-',$value)??'';return trim($value,'-')?:'post';} }
