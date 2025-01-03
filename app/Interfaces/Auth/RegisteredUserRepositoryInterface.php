<?php

namespace App\Interfaces\Auth;

interface RegisteredUserRepositoryInterface
{
    public function create();
    public function store($request);
}
