<?php

namespace App\Interfaces;

interface ProfileRepositoryInterface
{
    public function edit($request);
    public function update($request);
    public function destroy($request);
}
