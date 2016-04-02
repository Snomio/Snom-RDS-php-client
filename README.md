# Snom-RDS-php-client

Snom Redirection service XML-RPC PHP client

## Description

This tool is a simple web interface to the XML-RPC redirection service.

In order to install it you need the PHP interpreter with **xml-rpc** extension installed.

## Usage

If you already have a web server with PHP installed you just need to clone this repository in your server web directory.

#### Usage in a Docker container

Into the `docker/Dockerfile` file you can find a small Dockerfile ready to be used to test this app:

1. Clone this repo:

    ```
    $ git clone git@github.com:Snomio/Snom-RDS-php-client.git
    ```

1. Build the docker image:

    ```
    $ cd docker
    $ docker build -t apache-php .
    ```

1. Run the container:

    ```
    $ docker run -it -p 8080:80 -v $(PWD):/var/www/html apache-php
    ```

1. Connect to the container using your browser `http://127.0.0.1:8080`
