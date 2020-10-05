# test-task-iiko
## how to run
git clone https://github.com/alexandr-krylov/test-task-iiko.git

cd test-task-iiko

docker-compose up
## what works bad
1. saving invoice after removing lines
2. find invoices by date and status does not attach lines
## how to increase speed for findByDateStatus
create index for field date and field status
## how to increase speed for findByDateStatus for a lot more records
1. explode field date for fields year, month, day, hour, etc
2. create indexes with the fields
