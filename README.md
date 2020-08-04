# SnowflakeGenerator
Custom id generator for doctrine implementing the snowflake algorithm. Using [godruoyi/php-snowflake](https://github.com/godruoyi/php-snowflake).

# Install
```console
$ composer require subiabre/doctrine-snowflakes
```

# Usage
In your entity classes:

```php
/**
 * @ORM\Id()
 * @ORM\GeneratedValue(strategy="CUSTOM")
 * @ORM\CustomIdGenerator(class="Subiabre\SnowflakeGenerator")
 * @ORM\Column(type="bigint")
 */
private $id;
```

Take in consideration that PHP does not have a proper `bigint` data type. Due to this limitation, snowflake ids are treated as `string`.

```php
public function getId(): ?string
...
```
