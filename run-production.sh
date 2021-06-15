#! /bin/bash
docker run -it --rm --name democi4 \
-p 8081:80 \
-v $(pwd)/public/files:/var/www/html/public/files \
-v $(pwd)/.env:/var/www/html/.env \
democi4:v1