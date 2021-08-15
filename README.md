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
use Dgame\DataTransferObject\DataTransfer;

final class Limit
{
    use DataTransfer;

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
use Dgame\DataTransferObject\DataTransfer;

final class Limit
{
    use DataTransfer;

    public int $offset;
    #[Alias('size')]
    public int $limit;
}
```

Now the keys `size` **and** `limit`  will be mapped to the property `$limit`. You can mix `#[Name(...)]` and `#[Alias(...)]` as you want:

```php
use Dgame\DataTransferObject\Annotation\Alias;
use Dgame\DataTransferObject\Annotation\Name;
use Dgame\DataTransferObject\DataTransfer;

final class Foo
{
    use DataTransfer;

    #[Name('a')]
    #[Alias('z')]
    public int $id;
}
```

The keys `a` and `z` are mapped to the property `id` - but not the key `id` since you overwrote it with `a`. But the following

```php
use Dgame\DataTransferObject\Annotation\Alias;
use Dgame\DataTransferObject\DataTransfer;

final class Foo
{
    use DataTransfer;

    #[Alias('a')]
    #[Alias('z')]
    public int $id;
}
```

will accept the keys `a`, `z` and `id`.

## Transformations

If you want to _transform_ a value **before** it is assigned to the property, you can use Transformations.
You just need to implement the _Transformation_ interface. 

### Cast

_Cast_  is currently the only built-in Transformation and let you apply a Type-Cast **before** the value is assigned to the property:

If not told otherwise, a simple type-cast is performed. In the example below it would just call something like `$this->id = (int) $id`:

```php
use Dgame\DataTransferObject\Annotation\Cast;

final class Foo
{
    use DataTransfer;

    #[Cast]
    public int $id;
}
```

But that would be tried for **any** input. If you want to limit this to certain types, you can use `types`:

```php
use Dgame\DataTransferObject\Annotation\Cast;

final class Foo
{
    use DataTransfer;

    #[Cast(types: ['string', 'float', 'bool'])]
    public int $id;
}
```

Here the cast would only be performed if the incoming value is either an `int`, `string`, `float` or `bool`. 

If you want more control, you can use a static method inside of the class:

```php
use Dgame\DataTransferObject\Annotation\Cast;

final class Foo
{
    use DataTransfer;

    #[Cast(method: 'toInt', class: self::class)]
    public int $id;

    public static function toInt(string|int|float|bool $value): int
    {
        return (int) $value;
    }
}
```

or a function:

```php
use Dgame\DataTransferObject\Annotation\Cast;

function toInt(string|int|float|bool $value): int
{
    return (int) $value;
}

final class Foo
{
    use DataTransfer;

    #[Cast(method: 'toInt')]
    public int $id;
}
```

If a class is given but not a `method`, by default `__invoke` will be used:

```php
use Dgame\DataTransferObject\Annotation\Cast;

final class Foo
{
    use DataTransfer;

    #[Cast(class: self::class)]
    public int $id;

    public function __invoke(string|int|float|bool $value): int
    {
        return (int) $value;
    }
}
```

## Validation

You want to validate the value before it is assigned? We can do that. There are a few pre-defined validations prepared, but you can easily write your own by implementing the `Validation`-interface.

### Min

```php
use Dgame\DataTransferObject\Annotation\Min;
use Dgame\DataTransferObject\DataTransfer;

final class Limit
{
    use DataTransfer;
    
    #[Min(0)]
    public int $offset;
    #[Min(0)]
    public int $limit;
}
```

Both `$offset` and `$limit` must be at least have the value `0` (so they must be positive-integers). If not, an exception is thrown. You can configure the message of the exception by specifying the `message` parameter:

```php
use Dgame\DataTransferObject\Annotation\Min;
use Dgame\DataTransferObject\DataTransfer;

final class Limit
{
    use DataTransfer;
    
    #[Min(0, message: 'Offset must be positive!')]
    public int $offset;
    #[Min(0, message: 'Limit must be positive!')]
    public int $limit;
}
```

### Max

```php
use Dgame\DataTransferObject\Annotation\Max;
use Dgame\DataTransferObject\DataTransfer;

final class Limit
{
    use DataTransfer;
    
    #[Max(1000)]
    public int $offset;
    #[Max(1000)]
    public int $limit;
}
```

Both `$offset` and `$limit` may not exceed `1000`. If they do, an exception is thrown. You can configure the message of the exception by specifying the `message` parameter:

```php
use Dgame\DataTransferObject\Annotation\Max;
use Dgame\DataTransferObject\DataTransfer;

final class Limit
{
    use DataTransfer;
    
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
use Dgame\DataTransferObject\DataTransfer;

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
    use DataTransfer;

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
use Dgame\DataTransferObject\DataTransfer;

final class Foo
{
    use DataTransfer;

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
use Dgame\DataTransferObject\DataTransfer;

final class Foo
{
    use DataTransfer;

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
use Dgame\DataTransferObject\DataTransfer;

final class Foo
{
    use DataTransfer;

    #[Required(reason: 'We need an "id"')]
    public ?int $id;
    
    #[Required(reason: 'We need a "name"')]
    public string $name;
}

Foo::from(['id' => 42, 'name' => 'abc']); // Works
Foo::from(['name' => 'abc']); // Fails but would work without the `Required`-Attribute since $id is nullable
Foo::from(['id' => 42]); // Fails and would fail regardless of the `Required`-Attribute since $name is not nullable and has no default-value - but the reason why it is required is now more clear.
```

## Optional

The counterpart of [Required](#required).
If you don't want to or can't provide a default/nullable value, `Optional` will assign the **default value** of the property-type in case of a missing value:

```php
final class Foo
{
    use DataTransfer;
    
    #[Optional]
    public int $id;
}

$foo = Foo::from([]);
assert($foo->id === 0);
```

Of course you can specify which value should be used if no data is provided:

```php
final class Foo
{
    use DataTransfer;
    
    #[Optional(value: 42)]
    public int $id;
}

$foo = Foo::from([]);
assert($foo->id === 42);
```

In case you're using `Optional` together with a provided default-value, the default-value has always priority:

```php
final class Foo
{
    use DataTransfer;
    
    #[Optional(value: 42)]
    public int $id = 23;
}

$foo = Foo::from([]);
assert($foo->id === 23);
```

## Path

Did you ever wanted to extract a value from a provided array? `Path` to the rescue:

```php
final class Person
{
    use DataTransfer;

    #[Path('person.name')]
    public string $name;
}
```

It helps while with JSON's special `$value` attribute

```php
final class Person
{
    use DataTransfer;

    #[Path('married.$value')]
    public bool $married;
}
```

and with XML's `#text`.

```php
final class Person
{
    use DataTransfer;

    #[Path('first.name.#text')]
    public string $firstname;
}
```

---

But we can do even more. You can choose which parts of the field are taken

```php
final class Person
{
    use DataTransfer;

    #[Path('child.{born, age}')]
    public array $firstChild = [];
}
```

and can even assign them directly to an object:

```php
final class Person
{
    use DataTransfer;
    
    public int $id;
    public string $name;
    public ?int $age = null;

    #[Path('ancestor.{id, name}')]
    public ?self $parent = null;
}
```

## SelfValidation

In addition to the [customary validations](#validation) you can specify a class-wide validation after **all** assignments are done:

```php
#[SelfValidation(method: 'validate')]
final class SelfValidationStub
{
    use DataTransfer;

    public function __construct(public int $id)
    {
    }

    public function validate(): void
    {
        assert($this->id > 0);
    }
}
```

## ValidationStrategy

The default validation strategy is **fail-fast** which means an Exception is thrown as soon as an error is detected.
But that might not desirable, so you can configure this with a `ValidationStrategy`:

```php
#[ValidationStrategy(failFast: false)]
final class Foo
{
    use DataTransfer;

    #[Min(3)]
    public string $name;
    #[Min(0)]
    public int $id;
}

Foo::from(['name' => 'a', 'id' => -1]);
```

The example above would throw a combined exception that `name` is not long enough and `id` must be at least 0.
You can configure this as well by extending the `ValidationStrategy` and provide a `FailureHandler` and/or a `FailureCollection`.

# Property promotion

In the above examples, [property promotion](https://stitcher.io/blog/constructor-promotion-in-php-8) is not always used because it is more readable that way, but property promotion is supported. So the following example

```php
use Dgame\DataTransferObject\Annotation\Min;
use Dgame\DataTransferObject\DataTransfer;

final class Limit
{
    use DataTransfer;
    
    #[Min(0)]
    public int $offset;
    #[Min(0)]
    public int $limit;
}
```

can be rewritten as shown below

```php
use Dgame\DataTransferObject\Annotation\Min;
use Dgame\DataTransferObject\DataTransfer;

final class Limit
{
    use DataTransfer;

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
use Dgame\DataTransferObject\DataTransfer;

final class Bar
{
    public int $id;
}

final class Foo
{
    use DataTransfer;
    
    public Bar $bar;
}

$foo = Foo::from(['bar' => ['id' => 42]]);
echo $foo->bar->id; // 42
```

Have you noticed the missing `From` in `Bar`? `From` is just a little wrapper for the actual DTO. So your nested classes don't need to use it at all.

There is no limit to the depth of nesting, the responsibility is yours! :)
