<?php

class InvoiceRepository implements InterfaceRepository
{
    protected PDO $dbh;
    public function __construct()
    {
        do
            try {
                $this->dbh = new PDO('mysql:host=mysql;dbname=iiko', 'user', 'password');
            } catch (PDOException $pdoe) {
                echo $pdoe->getMessage();
                sleep(1);
            }
        while (!isset($this->dbh));
    }

    public function save(Invoice $invoice)
    {
        $result = $this
            ->dbh
            ->query('SELECT id FROM invoice WHERE number = "' . $invoice->getNumber() . '"');
        if ($result->rowCount()) {
            $this->dbh->exec('UPDATE invoice SET ' .
            'status = "' . $invoice->getStatus() . '", ' .
            'date = "' . $invoice->getDate()->format('Y-m-d H:i:s') . '", ' .
            'discount = "' . $invoice->getDiscount() . '" ' .
            'WHERE number = "' . $invoice->getNumber() . '"');
            $id = $result->fetch()['id'];
        } else {
            $this->dbh->exec('INSERT invoice (number, status, date, discount) VALUES ("' .
            $invoice->getNumber() . '", "' .
            $invoice->getStatus() . '", "' .
            $invoice->getDate()->format('Y-m-d H:i:s'). '", "' .
            $invoice->getDiscount() . '")');
            $id = $this->dbh->lastInsertId();
        }
        foreach($invoice->getInvoiceLines()->getAll() as $line) {
            if (
                $this
                ->dbh
                ->query('SELECT id FROM line WHERE id = "' . $line->getId() . '"')
                ->rowCount()
                ) {
                    $this
                    ->dbh
                    ->exec('UPDATE line SET amount = "' . $line->getAmount() . '", ' .
                            'quantity = "' . $line->getQuantity() . '", '.
                            'name = "' . $line->getName() . '", ' .
                            'invoice = "' . $id . '" ' .
                            'WHERE id = "' . $line->getId() . '"');
                } else {
                    $this->dbh->exec('INSERT line (id, amount, quantity, name, invoice) VALUES ("' .
                    $line->getId() . '", "' .
                    $line->getAmount() . '", "' .
                    $line->getQuantity() . '", "' .
                    $line->getName() . '", "' .
                    $id  . '")');
                }
        }
    }

    public function findByDateStatus(DateTime $date, $status)
    {
        $result = $this
            ->dbh
            ->query('SELECT * FROM invoice '.
            'WHERE date > "' . $date->format('Y-m-d H:i:s') . '" AND status = "' . $status . '"')
            ->fetchAll();
        $forReturn = new SplObjectStorage();
        foreach ($result as $row) {
            $forReturn->attach((new Invoice(new InvoiceLines()))
                ->setNumber($row['number'])
                ->setDate(new DateTime($row['date']))
                ->setStatus($row['status'])
                ->setDiscount($row['discount']));
        }
        return $forReturn;
    }

    public function findByNumber($number) : Invoice
    {
        $result =
            $this
            ->dbh
            ->query('SELECT * FROM invoice WHERE number = "' . $number . '"')
            ->fetch();
        $lines = $this
            ->dbh
            ->query('SELECT * FROM line WHERE invoice = "' . $result['id'] . '"')
            ->fetchAll();
        $invoiceLines = new InvoiceLines();
        foreach ($lines as $line) {
            $invoiceLines->add((new InvoiceLine())
            ->setId($line['id'])
            ->setAmount($line['amount'])
            ->setQuantity($line['quantity'])
            ->setName($line['name']));
        }
        return (new Invoice($invoiceLines))
            ->setNumber($result['number'])
            ->setStatus($result['status'])
            ->setDate(new DateTime($result['date']))
            ->setDiscount($result['discount']);
    }
}