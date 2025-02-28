<?php

use Carbon\Carbon;
// convert tanggal indonesia ke format inggris
if (!function_exists('convertDmyToYmd')) {
    function convertDmyToYmd($date)
    {
        $result = str_replace('/', '-', $date);
        return Carbon::parse($result)->format('Y-m-d');
    }
}

// convert tanggal inggris ke format indonesia
if (!function_exists('convertYmdToDmy')) {
    function convertYmdToDmy($date)
    {
        $result = str_replace('-', '/', $date);
        return Carbon::parse($result)->format('d/m/Y');
    }
}
