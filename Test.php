<?php

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function setUp(): void
    {
        spl_autoload_register();
    }

    public function testTest()
    {
        $invoice = new Invoice(new InvoiceLines());
        $this->assertInstanceOf(Invoice::class, $invoice);
        $invoiceLine = new InvoiceLine();
    }

    public function getSetNumberProvider()
    {
        return [
            [1],
            ['number']
        ];
    }

    /**
     * @dataProvider getSetNumberProvider
     */
    public function testSetGetNumber($number)
    {
        $invoice = new Invoice(new InvoiceLines());
        $invoice->setNumber($number);
        $this->assertSame($number, $invoice->getNumber());
    }

    public function getSetStatusProvider()
    {
        return [
            ['новый'],
            ['оплачен']
        ];
    }

    /**
     * @dataProvider  getSetStatusProvider
     */
    public function testSetGetStatus($status)
    {
        $invoice = new Invoice(new InvoiceLines());
        $invoice->setStatus($status);
        $this->assertSame($status, $invoice->getStatus());
    }

    public function setGetDateProvider()
    {
        return [
            [new DateTime()]
        ];
    }

    /**
     * @dataProvider setGetDateProvider
     */
    public function testSetGetDate($date)
    {
        $invoice = new Invoice(new InvoiceLines());
        $invoice->setDate($date);
        $this->assertSame($date, $invoice->getDate());
    }

    public function setGetDiscountProvider()
    {
        return [
            [0],
            [5],
            [15]
        ];
    }

    /**
     * @dataProvider setGetDiscountProvider
     */
    public function testSetGetDiscount($discount)
    {
        $invoice = new Invoice(new InvoiceLines());
        $invoice->setDiscount($discount);
        $this->assertSame($discount, $invoice->getDiscount());
    }

}
