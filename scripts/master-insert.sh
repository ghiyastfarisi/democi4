#! /bin/bash
echo "executing import..."
mysql --protocol=tcp -u root -p1XAmpleXample1 db_upi_dacen < master.sql
echo "executing done"
sleep 3
echo "checking tables..."
mysql --protocol=tcp -u root -p1XAmpleXample1 db_upi_dacen -e "show tables"