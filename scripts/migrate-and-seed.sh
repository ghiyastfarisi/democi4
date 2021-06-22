#! /bin/bash
docker exec -it democi4 php spark migrate
docker exec -it democi4 php spark db:seed UserSeeder
docker exec -it democi4 php spark db:seed UpiSeeder
docker exec -it democi4 php spark db:seed UpiProduksiSeeder