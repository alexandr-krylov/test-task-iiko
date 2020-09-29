<?php

class Invoice
{
    private $number;
    private $status;
    protected DateTime $date;    //DateTime
    protected $discount;
    protected InterfaceLines $invoiceLines;

    public function __construct(InterfaceLines $lines)
    {
        $this->invoiceLines = $lines;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function  setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getDate() : DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setDiscount($discount)
    {
        $this->discount = $discount;
        return $this;
    }

    public function addLine(InvoiceLine $line)
    {
        $this->invoiceLines->add($line);
        return $this->invoiceLines;
    }

    public function getTotal()
    {
        return ($this->invoiceLines->sum()) * (1 - $this->discount / 100);
    }

}