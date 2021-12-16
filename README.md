# medibank_api_challenge
Medibank Code Challenge

## Background
Medibank Coding Challenge (API), refer to: https://github.com/medibank-digital/api

## Technical Details
This coding challenge is written using PHP and lightweight REST slim framework https://github.com/slimphp/Slim

## Prerequisites
1. LAMP (Linux, Apache, MySQL, PHP) stacks has been installed in your environment. <br/>
   Place this code in the DOCUMENT_ROOT of your web server. <br/>
   <b>PHP 7.3 above, enable php-mbstring, enable php-xml</b>
2. Composer is installed in your environment. <br/>
   Refer to: https://getcomposer.org/download/

## How to run
Step 1. Clone this code repository. <br/>
Step 2. Run $<b>composer install</b> <br/> To add dependencies in your application. <br/>
Step 3. Run $<b>composer require unsplash/unsplash</b> <br/> To add unsplash library. <br/>
Step 4. Run $<b> composer require slim/http-cache</b> <br/> To install http-cache : composer require slim/http-cache <br/>
<em>[ec2-user@ip-172-31-30-99 medibank_api_challenge]$ composer require slim/http-cache</em>

## Public Access
This project is being hosted publicly until 31 Dec 21 in Amazon Cloud (Linux). <br/>
To access per challenge requirement: http://ec2-54-153-153-254.ap-southeast-2.compute.amazonaws.com/api/article
<br/><br/>
Alternatively, you can access through (docker container running on port 8000): http://ec2-54-153-153-254.ap-southeast-2.compute.amazonaws.com:8000/api/article <br/>
Docker hub: https://hub.docker.com/repository/docker/jsusanto/medibank_js_webapi <br/>
<br/>
Here is the ```js docker-compose.yml ```

```js
version: '3'

services:
  backend:
    image: 'jsusanto/medibank_js_webapi:latest'
    environment:
      UNSPLASH_ACCESS_KEY: 'CUVuiHGTg6_srEup4NaPqfZW-wr7yQvSrDX0FO-BAz4'
    expose:
      - '3000'
  frontend:
    image: 'medibankdigital/hacker-card-frontend:latest'
    depends_on:
      - backend
    expose:
      - '3001'
  nginx:
    image: nginx:latest
    volumes:
      - ./nginx.conf:/etc/nginx/nginx.conf:ro
    depends_on:
      - frontend
    ports:
      - '4000:4000'
```
