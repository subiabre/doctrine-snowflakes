# SnowflakeGenerator
Custom Doctrine ID Generator implementing the snowflake algorithm.

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

Take in consideration that PHP does not have a proper `bigint` data type. Due to this limitation, doctrine-snowflake ids should be used as`string`.

```php
public function getId(): string
```
