<?php

namespace App\Interfaces;

interface BookRepositoryInterface
{
    public function all();
    public function fetchBooks($request);
    public function borrow($request, $id);
    public function exportPDF();
    public function create();
    public function store($request);
    public function edit($id);
    public function update($request, $id);
    public function destroy($id);
    public function returnBook($request, $id);
}
