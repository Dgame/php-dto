# Data Transfer Object

Want to serialize an object with data on the fly? Go for it by using the `From` trait.

## Name

You get a parameter which is not named as the parameter in your class? `#[Name(...)]` to the rescue - just specify the name from the Request:

```php
use Dgame\DataTransferObject\Annotation\Name;
use Dgame\DataTransferObject\From;

final class Limit
{
    use From;

    public int $offset;
    #[Name('size')]
    public int $limit;
}
```

Now the key `size` will be mapped to the property `$limit` - but keep in mind: the name `limit` is no longer known
 since you overwrote it with `size`. If that is not your intention, take a look at the [Alias](#alias) Attribute.  

## Alias

You get a parameter which is not **always** named as the parameter in your class? `#[Alias(...)]` can help you - just specify the alias from the Request:

```php
use Dgame\DataTransferObject\Annotation\Alias;
use Dgame\DataTransferObject\From;

final class Limit
{
    use From;

    public int $offset;
    #[Alias('size')]
    public int $limit;
}
```

Now the keys `size` **and** `limit`  will be mapped to the property `$limit`. You can mix `#[Name(...)]` and `#[Alias(...)]` as you want:

```php
use Dgame\DataTransferObject\Annotation\Alias;
use Dgame\DataTransferObject\Annotation\Name;
use Dgame\DataTransferObject\From;

final class Foo
{
    use From;

    #[Name('a')]
    #[Alias('z')]
    public int $id;
}
```

The keys `a` and `z` are mapped to the property `id` - but not the key `id` since you overwrote it with `a`. But the following

```php
use Dgame\DataTransferObject\Annotation\Alias;
use Dgame\DataTransferObject\From;

final class Foo
{
    use From;

    #[Alias('a')]
    #[Alias('z')]
    public int $id;
}
```

will accept the keys `a`, `z` and `id`.

## Call

You want to call a function or method before the value is assigned? No problem with `#[Call(<method>, <class>)]`. If you don't specify a method but just a class, the `__invoke` method is the default.

```php
use Dgame\DataTransferObject\Annotation\Call;
use Dgame\DataTransferObject\From;

final class Foo
{
    use From;

    #[Call(class: self::class, method: 'toInt')]
    public int $id;

    public static function toInt(string|int|float|bool $value): int
    {
        return (int) $value;
    }
}

$foo = Foo::from(['id' => '43']);
```

## Validation

You want to validate the value before it is assigned? No problem. There are a few pre-defined validations prepared, but you can easily write your own by implementing the `Validation`-interface.

### Min

```php
use Dgame\DataTransferObject\Annotation\Min;
use Dgame\DataTransferObject\From;

final class Limit
{
    use From;
    
    #[Min(0)]
    public int $offset;
    #[Min(0)]
    public int $limit;
}
```

Both `$offset` and `$limit` must be at least have the value `0` (so they must be positive-integers). If not, an exception is thrown. You can configure the message of the exception by specifying the `message` parameter:

```php
use Dgame\DataTransferObject\Annotation\Min;
use Dgame\DataTransferObject\From;

final class Limit
{
    use From;
    
    #[Min(0, message: 'Offset must be positive!')]
    public int $offset;
    #[Min(0, message: 'Limit must be positive!')]
    public int $limit;
}
```

### Max

```php
use Dgame\DataTransferObject\Annotation\Max;
use Dgame\DataTransferObject\From;

final class Limit
{
    use From;
    
    #[Max(1000)]
    public int $offset;
    #[Max(1000)]
    public int $limit;
}
```

Both `$offset` and `$limit` may not exceed `1000`. If they do, an exception is thrown. You can configure the message of the exception by specifying the `message` parameter:

```php
use Dgame\DataTransferObject\Annotation\Max;
use Dgame\DataTransferObject\From;

final class Limit
{
    use From;
    
    #[Max(1000, message: 'Offset may not be larger than 1000')]
    public int $offset;
    #[Max(1000, message: 'Limit may not be larger than 1000')]
    public int $limit;
}
```

### Instance

Want to make sure that a property is an instance of an certain class or that each item in an array is an instance of that said class?

```php
final class Collection
{
    #[Instance(class: Entity::class, message: 'We need an array of Entities!')]
    private array $entities;
}
```

### Custom

Want you own Validation? Just implements the `Validation`-interface:

```php
use Dgame\DataTransferObject\Annotation\Validation;
use Dgame\DataTransferObject\From;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class NumberBetween implements Validation
{
    public function __construct(private int|float $min, private int|float $max)
    {
    }

    public function validate(mixed $value): void
    {
        if (!is_numeric($value)) {
            throw new InvalidArgumentException(var_export($value, true) . ' must be a numeric value');
        }

        if ($value < $this->min) {
            throw new InvalidArgumentException(var_export($value, true) . ' must be >= ' . $this->min);
        }

        if ($value > $this->max) {
            throw new InvalidArgumentException(var_export($value, true) . ' must be <= ' . $this->max);
        }
    }
}

final class ValidationStub
{
    use From;

    #[NumberBetween(18, 125)]
    private int $age;

    public function getAge(): int
    {
        return $this->age;
    }
}
```

## Ignore

You don't want a specific key-value to override your property? Just ignore it:

```php
use Dgame\DataTransferObject\Annotation\Ignore;
use Dgame\DataTransferObject\From;

final class Foo
{
    use From;

    #[Ignore]
    public string $uuid = 'abc';
    public int $id = 0;
}

$foo = Foo::from(['uuid' => 'xyz', 'id' => 42]);
echo $foo->id; // 42
echo $foo->uuid; // abc
```

## Reject

You want to go one step further than simply ignoring a value? Then `Reject` it:

```php
use Dgame\DataTransferObject\Annotation\Reject;
use Dgame\DataTransferObject\From;

final class Foo
{
    use From;

    #[Reject(reason: 'The attribute "uuid" is not supposed to be set')]
    public string $uuid = 'abc';
}

$foo = Foo::from(['id' => 42]); // Works fine
echo $foo->id; // 42
echo $foo->uuid; // abc

$foo = Foo::from(['uuid' => 'xyz', 'id' => 42]); // throws 'The attribute "uuid" is not supposed to be set'
```

## Required

Normally, a nullable-property or a property with a provided default value is treatend with said default-value or null if the property cannot be assigned from the provided data.
If no default-value is provided and the property is not nullable, an error is thrown in case the property was not found.
But in some cases you might want to specify the reason, why the property is required or even want to require an otherwise default-able property. You can do that by using `Required`:

```php
use Dgame\DataTransferObject\Annotation\Required;
use Dgame\DataTransferObject\From;

final class Foo
{
    use From;

    #[Required(reason: 'We need an "id"')]
    public ?int $id;
    
    #[Required(reason: 'We need a "name"')]
    public string $name;
}

$foo1 = Foo::from(['id' => 42, 'name' => 'abc']); // Works
$foo2 = Foo::from(['name' => 'abc']); // Fails but would work without the `Required`-Attribute since $id is nullable
$foo3 = Foo::from(['id' => 42]); // Fails and would fail regardless of the `Required`-Attribute since $name is not nullable and has no default-value - but the reason why it is required is now more clear.
```
