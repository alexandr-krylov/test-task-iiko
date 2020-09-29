<?php

interface InterfaceLines
{
    public function add(InvoiceLine $line);
    public function remove($id);
    public function getSum();
    public function getById($id);
    public function getAll();
}