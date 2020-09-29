<?php

class InvoiceLines implements InterfaceLines
{

    protected SplObjectStorage $lines;

    public function __construct()
    {
        $this->lines = new SplObjectStorage();
    }

    public function add(InvoiceLine $line)
    {
        $lines->attach($line);
    }

    public function remove($id)
    {
        foreach ($lines as $line) {
            if ($id == $line->getId) {
                $lines->detach($line);
                break;
            }
        }
    }

    public function getSum()
    {
        $sum = 0;
        foreach($lines as $line) {
            $sum += $line->getAmount();
        }
    }

    public function getAll()
    {
        return $this->lines;
    }

    public function getById($id)
    {
        foreach($lines as $line) {
            if ($id == $line->getId()){
                return $line;
            }
        }
    }
}