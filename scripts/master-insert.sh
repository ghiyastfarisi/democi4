#! /bin/bash
echo "executing import..."
mysql -h 192.168.99.99 -u root -p1XAmpleXample1 db_democi4 < master.sql
echo "executing done"
sleep 3
echo "checking tables..."
mysql -h 192.168.99.99 -u root -p1XAmpleXample1 db_democi4 -e "show tables"