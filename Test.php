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

    public function invoiceRepositoryProvider()
    {
        spl_autoload_register();
        return [
            [(new Invoice((new InvoiceLines())
            ->add((new InvoiceLine())->setId(1)->setAmount(1000)->setQuantity(3)->setName('Line 1'))
            ->add((new InvoiceLine())->setId(2)->setAmount(550)->setQuantity(2)->setName('Line 2'))
            ))->setDiscount(5)
            ->setNumber('202010051')
            ->setStatus('new')
            ->setDate(new DateTime('202010050000')),
            [1, 2]
            ],
            [(new Invoice((new InvoiceLines())
            ->add((new InvoiceLine())->setId(3)->setAmount(1000)->setQuantity(3)->setName('Line 3'))
            ->add((new InvoiceLine())->setId(4)->setAmount(550)->setQuantity(2)->setName('Line 4'))
            ))->setDiscount(15)
            ->setNumber('202010052')
            ->setStatus('new')
            ->setDate(new DateTime('202010050001')),
            [3, 4]
            ]
        ];
    }
    /**
     * @dataProvider invoiceRepositoryProvider
     */
    public function testInvoiceRepository(Invoice $invoice, $lineIds)
    {
        $repositiry = new InvoiceRepository();
        $repositiry->save($invoice);
        $savedInvoice = $repositiry->findByNumber($invoice->getNumber());
        $this->assertSame($invoice->getNumber(), $savedInvoice->getNumber());
        $this->assertSame($invoice->getStatus(), $savedInvoice->getStatus());
        $this->assertSame(
            $invoice->getDate()->format('Y-m-d H:i:s'),
            $savedInvoice->getDate()->format('Y-m-d H:i:s'));
        $this->assertInstanceOf(DateTime::class, $savedInvoice->getDate());
        $this->assertSame($invoice->getDiscount(), $savedInvoice->getDiscount());
        $this->assertInstanceOf(InterfaceLines::class, $savedInvoice->getInvoiceLines());
        foreach ($lineIds as $lineId) {
            $this->assertSame(
                $invoice->getInvoiceLines()->getById($lineId)->getId(),
                $savedInvoice->getInvoiceLines()->getById($lineId)->getId());
            $this->assertSame(
                $invoice->getInvoiceLines()->getById($lineId)->getAmount(),
                $savedInvoice->getInvoiceLines()->getById($lineId)->getAmount());
            $this->assertSame(
                $invoice->getInvoiceLines()->getById($lineId)->getQuantity(),
                $savedInvoice->getInvoiceLines()->getById($lineId)->getQuantity());
            $this->assertSame(
                $invoice->getInvoiceLines()->getById($lineId)->getName(),
                $savedInvoice->getInvoiceLines()->getById($lineId)->getName());
        }
    }

    public function findByDateStatusProvider()
    {
        return
        [
            [
                [
                    (new Invoice(new InvoiceLines()))
                    ->setDiscount(5)
                    ->setNumber('20201011')
                    ->setStatus('Оплачен')
                    ->setDate(new DateTime('202001010000')),
                    (new Invoice(new InvoiceLines()))
                    ->setDiscount(15)
                    ->setNumber('202001021')
                    ->setStatus('Оплачен')
                    ->setDate(new DateTime('202001020000'))
                ],
                new DateTime('20200101'),
                'Оплачен'
            ]
        ];
    }

    /**
     * @dataProvider findByDateStatusProvider
     */
    public function testInvoiceRepositoryFindByDateStatus($invoices, DateTime $date, $status)
    {
        $repository = new InvoiceRepository();
        foreach ($invoices as $invoice) {
            $repository->save($invoice);
        }
        foreach ($repository->findByDateStatus($date, $status) as $invoice) {
            $this->assertSame($status, $invoice->getStatus());
            $this->assertGreaterThan($date, $invoice->getDate());
        }
    }
}
