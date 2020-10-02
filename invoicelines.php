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
        $this->lines->attach($line);
        return $this;
    }

    public function remove($id)
    {
        foreach ($this->lines as $line) {
            if ($id == $line->getId()) {
                $this->lines->detach($line);
                break;
            }
        }
    }

    public function getSum()
    {
        $sum = 0;
        foreach($this->lines as $line) {
            $sum += $line->getAmount() * $line->getQuantity();
        }
        return $sum;
    }

    public function getAll()
    {
        return $this->lines;
    }

    public function getById($id)
    {
        foreach($this->lines as $line) {
            if ($id == $line->getId()){
                return $line;
            }
        }
    }
}