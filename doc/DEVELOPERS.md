# Developing alloc

## Docker Compose

as easy as:

```bash
composer require -n
docker compose up -d --build
```

`composer` will install the dependancies required by alloc, and `docker compose`
will build the images and start them.

## Coding Standards

### PHP

We try to follow the [PSR-12](https://www.php-fig.org/psr/psr-12/) coding
standard wherever possible.

Install `rector` and `php-cs-fixer` with:

```bash
composer require --dev -n
```

Then you can run:

```bash
./vendor/bin/ rector process && ./vendor/bin/php-cs-fixer fix
```

Because the alloc code base did not use PSR-2 until late 2018, many class/method
names do not match the PSR-2 recommendations, this is an ongoing effort.

##$ Javascript

Use `prettier` for formatting Javascript, installing with `yarn` is as easy as:

```bash
yarn
```

Then to format:

```bash
./node_modules/.bin/prettier -w javascript/src/zz_alloc.js
```