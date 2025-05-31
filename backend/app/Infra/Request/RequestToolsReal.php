<?php

namespace App\Infra\Request;

class RequestToolsReal
{
    public function isApplicationInDevelopMode(): bool
    {
        return config('app.env') != 'production';
    }
}
