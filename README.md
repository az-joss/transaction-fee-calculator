# Transaction Fee Calculator

This is was created for test purpose only.

## Installation

1. Clone repo

```sh
git clone az-joss/transaction-fee-calculator
```

2. Run composer install in the project dir

```sh
composer install
```

## Set up

By default `Dummy*` providers are used and they do not require any additon set up.

For working with external api you need to provide its config via `.env` file. (See `.env.example`) frst.

Also to anable it change provider in related facade class.

E.g. to use `ExchangeRatesApi` need to create `ExchangeRatesApiProvider` via `ExchangeRateFactory` that is used in `ExchangeRateFacade`.

## Usage

Simply run command from project root dir

```sh
php calculate-transaction-fees.php input.txt
```

To use `.env` run like this

```sh
php-with-env.sh calculate-transaction-fees.php input.txt
```

### Testing

```sh
./vendor/bin/phpunit --bootstrap=tests/bootstrap.php tests/functional     
```

## Major principals

- code isolation
- code single responsibility
- expandability
- testability

### Code aggreements

- factory methods `provide*` is supposed to be resolved via DI container for furture. It was skiped in favor for speed nad size.
- tests are focused on facade only, and requires mocks for external dependencie (eg resolved via factory `provide*`methods)

## License

MIT