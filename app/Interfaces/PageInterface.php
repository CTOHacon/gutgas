<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface PageInterface
{
    public function show(string $id);
}
