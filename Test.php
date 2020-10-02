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

    public function addRemoveLineProvider()
    {
        spl_autoload_register();
        return [
            [(new InvoiceLine())->setId(1)->setAmount(1000)->setQuantity(3)->setName('Line 1')],
            [(new InvoiceLine())->setId(2)->setAmount(550)->setQuantity(2)->setName('Line 2')]
        ];
    }

    /**
     * @dataProvider addRemoveLineProvider
     */
    public function testAddRemoveLine($line)
    {
        $invoice = new Invoice(new InvoiceLines());
        $invoice->getInvoiceLines()->add($line);
        $this->assertSame($line, $invoice->getInvoiceLines()->getById($line->getId()));
        $invoice->getInvoiceLines()->remove($line->getId());
        $this->assertNull($invoice->getInvoiceLines()->getById($line->getId()));
    }

    public function getTotalProvider()
    {
        spl_autoload_register();
        return [
            [(new Invoice((new InvoiceLines())
            ->add((new InvoiceLine())->setId(1)->setAmount(1000)->setQuantity(3)->setName('Line 1'))
            ->add((new InvoiceLine())->setId(2)->setAmount(550)->setQuantity(2)->setName('Line 2'))
            ))->setDiscount(5)
            ,
            3895
            ],
            [(new Invoice((new InvoiceLines())
            ->add((new InvoiceLine())->setId(1)->setAmount(1000)->setQuantity(3)->setName('Line 1'))
            ->add((new InvoiceLine())->setId(2)->setAmount(550)->setQuantity(2)->setName('Line 2'))
            ))->setDiscount(15)
            ,
            3485
            ]
        ];
    }

    /**
     * @dataProvider getTotalProvider
     */
    public function testGetTotal($invoice, $expectedTotal)
    {
        $this->assertEquals($expectedTotal, $invoice->getTotal());
    }

}
