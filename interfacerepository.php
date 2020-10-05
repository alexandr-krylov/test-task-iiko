<?php

interface InterfaceRepository
{
    public function save(Invoice $invoice);
    public function findByDateStatus(DateTime $date, $status);
    public function findByNumber($number) : Invoice;
}