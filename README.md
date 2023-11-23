# SnowflakeGenerator
ID Generator for Doctrine implementing the Snowflake algorithm.

# Install
```console
$ composer require subiabre/doctrine-snowflakes
```

# Usage
In your entity classes:

```php
#[ORM\Id()]
#[ORM\GeneratedValue(strategy: "CUSTOM")]
#[ORM\CustomIdGenerator(class: SnowflakeGenerator::class)]
#[ORM\Column(type: Types::BIGINT]
private string $id;
```

Take in consideration that PHP does not have a proper `bigint` data type. Due to this limitation, doctrine-snowflake IDs should be used as `string`.

```php
public function getId(): string
```

# Testing
This package includes unit tests with the PHPUnit library. Run the tests with:
```console
./vendor/bin/phpunit
```

The default test cases test against concurrency and uniqueness in 10 batches of 300 IDs each batch, alternatively you can supply any batch size with: 
```console
BATCH_SIZE=1000 ./vendor/bin/phpunit
```
