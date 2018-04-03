Symfony 3 Test PHP Paymentwall
======================
Installation
------------

PHP >= 5.5.9 required

## Installing Composer

Composer is the dependency manager used by modern PHP applications and it can also be used to create new applications.

Download the installer from [getcomposer.org/download](https://getcomposer.org/download/), execute it and follow the instructions.

## Get Project

  ```
  git clone https://github.com/minhvb/test_php_paymentwall.git test_php
  ```

Install packages

  ```
  cd test_php
  composer install
  ```

Initial Database

  ```
  cd test_php
  bin/console doctrine:database:create --if-not-exists
  bin/console doctrine:schema:update --force
  ```

Customize Config file
  ```
  sudo vi (or nano) test_php/app/config/parameters.yml
  ```
Then custom your configs like DB port, username and password etc.

## Cli mode
  ```
  cd test_php
  ```
To create feed:
  ```
  bin/console minhvb:feed:create (arg1: title: required) (arg2: url: required)
  ex:
  bin/console minhvb:feed:create "VNe" "https://vnexpress.net/rss/tin-moi-nhat.rss"
  ```
To edit feed:
  ```
  bin/console minhvb:feed:edit (arg1: id: required) (opt1: title) (opt2: url)
  ex:
  bin/console minhvb:feed:edit 1 --title="New Title" --url="New URL"
  ```
To delete feed:
  ```
  bin/console minhvb:feed:delete (arg1: id: required)
  ex:
  bin/console minhvb:feed:delete 1

To show feed list:
  ```
  bin/console minhvb:feed:list

To read feed content:
  ```
  bin/console minhvb:feed:read (arg1: id: required)
  ex:
  bin/console minhvb:feed:read 1
