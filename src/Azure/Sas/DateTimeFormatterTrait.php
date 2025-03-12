<?php

namespace App\Azure\Sas;

trait DateTimeFormatterTrait
{
    private function formatDate(int $timestamp): string
    {
        return date('Y-m-d\TH:i:s', $timestamp) . 'Z';
    }
}