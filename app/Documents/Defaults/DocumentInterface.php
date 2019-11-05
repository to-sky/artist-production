<?php

namespace App\Documents\Defaults;


interface DocumentInterface
{
    public function download($tag = '');

    public function link($tag = '');

    public function path($tag = '');

    public function print($tag = '');
}