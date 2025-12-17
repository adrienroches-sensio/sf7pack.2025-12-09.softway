Sensio Event
============

## Install

```console
$ git clone https://github.com/adrienroches-sensio/sf7pack.2025-12-09.softway.git
$ cd ./sf7pack.2025-12-09.softway
$ symfony composer install
$ symfony console doctrine:migration:migrate -n
$ symfony console doctrine:fixtures:load -n
$ symfony serve
```

## Log in

| username | password | roles      |
|----------|----------|------------|
| admin    | admin    | ROLE_ADMIN |
