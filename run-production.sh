#! /bin/bash
docker stop democi4
docker rm democi4
docker run -d --name democi4 \
    --restart=always \
    --network=bridge \
    -p 80:80 \
    -v $(pwd)/public/files:/var/www/html/public/files \
    -v $(pwd)/.env:/var/www/html/.env \
    democi4:v1