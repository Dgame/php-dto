# Data Transfer Object

Want to deserialize an object with data on the fly? Go for it by using the `From` trait.

---

How is this package any different from [spaties](https://github.com/spatie) popular [data-transfer-object](https://github.com/spatie/data-transfer-object), you may ask?
Well, it's not meant to be a replacement by any means. But while using it I've often come across some things I've missed since I knew them from [serde](https://serde.rs/), like renaming and ignoring properties, something that spatie's _data-transfer-object_ [might not get](https://github.com/spatie/data-transfer-object/issues/142#issuecomment-690418112) in the near future.
So there it is, my own little DTO package :) I hope it helps someone, as it helps me in my daily work.
Feel free to open issues or pull requests - any help is greatly appreciated!

### Requirements

This package is designed for PHP &GreaterEqual; 8.0 only since it's using [PHP 8.0 Attributes](https://stitcher.io/blog/attributes-in-php-8).

# Attributes

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

You want to validate the value before it is assigned? We can do that. There are a few pre-defined validations prepared, but you can easily write your own by implementing the `Validation`-interface.

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

Do you want to make sure that a property is an instance of a certain class or that each item in an array is an instance of that said class?

```php
use Dgame\DataTransferObject\Annotation\Instance;

final class Collection
{
    #[Instance(class: Entity::class, message: 'We need an array of Entities!')]
    private array $entities;
}
```

### Type

If you are trying to cover objects or other class instances, you should probably take a look at [Instance](#instance).

As long as you specify a type for your properties, the `Type` validation is automatically added to ensure that the specified values can be assigned to the specified types. If not, a validation exception will be thrown.
Without this validation, a `TypeError` would be thrown, which may not be desirable.

So this code
```php
final class Foo
{
    private ?int $id;
}
```

is actually seen as this:
```php
use Dgame\DataTransferObject\Annotation\Type;

final class Foo
{
    #[Type(name: '?int')]
    private ?int $id;
}
```

The following snippets are equivalent to the snippet above:

```php
use Dgame\DataTransferObject\Annotation\Type;

final class Foo
{
    #[Type(name: 'int|null')]
    private ?int $id;
}
```

```php
use Dgame\DataTransferObject\Annotation\Type;

final class Foo
{
    #[Type(name: 'int', allowsNull: true)]
    private ?int $id;
}
```

---

If you want to change the exception message, you can do so using the `message` parameter:

```php
use Dgame\DataTransferObject\Annotation\Type;

final class Foo
{
    #[Type(name: '?int', message: 'id is expected to be int or null')]
    private ?int $id;
}
```

### Custom

Do you want your own Validation? Just implement the `Validation`-interface:

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

Normally, a nullable-property or a property with a provided default value is treated with said default-value or null if the property cannot be assigned from the provided data.
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

Foo::from(['id' => 42, 'name' => 'abc']); // Works
Foo::from(['name' => 'abc']); // Fails but would work without the `Required`-Attribute since $id is nullable
Foo::from(['id' => 42]); // Fails and would fail regardless of the `Required`-Attribute since $name is not nullable and has no default-value - but the reason why it is required is now more clear.
```

# Property promotion

In the above examples, [property promotion](https://stitcher.io/blog/constructor-promotion-in-php-8) is not used because it is more readable that way, but property promotion is supported. So the following example
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

can be rewritten as shown below

```php
use Dgame\DataTransferObject\Annotation\Min;
use Dgame\DataTransferObject\From;

final class Limit
{
    use From;

    public function __construct(
        #[Min(0)] public int $offset,
        #[Min(0)] public int $limit
    ) { }
}
```

and it still works.

# Nested object detection

You have nested objects and want to deserialize them all at once? That is a given:

```php
use Dgame\DataTransferObject\From;

final class Bar
{
    public int $id;
}

final class Foo
{
    use From;
    
    public Bar $bar;
}

$foo = Foo::from(['bar' => ['id' => 42]]);
echo $foo->bar->id; // 42
```

Have you noticed the missing `From` in `Bar`? `From` is just a little wrapper for the actual DTO. So your nested classes don't need to use it at all.

There is no limit to the depth of nesting, the responsibility is yours! :)
