![Vector Core](./logo.png)
[![Badge Status](https://img.shields.io/badge/badge%20status-dank-brightgreen.svg)](https://niceme.me/)

## The Elevator Pitch
Vector gives you php functional superpowers.
- The evolution:
    - _Native PHP_
        ```php
          array_sum(
              array_map(
                  fn($a) => $a + 1,
                  [1, 2, 3]
              )
          );
          // 9
        ```
        - 👎 More than 1 or 2 function chains is unmaintainable
    - _Laravel Collections_
        ```php
          collect([1, 2, 3])
              ->map(fn($a) => $a + 1)
              ->sum();
              // 9
        ```
        - 👍 More than 1 or 2 function chains is unmaintainable
        - 👎 Unfortunately you can't do this with every type in the same elegant way (only works with collections)
    -  _Vector_
        ```php
           vector([1, 2, 3])
               ->pipe(Arrays::map(Math::add(1))) // or `fn($a) => $a + 1)` 
               ->pipe(Math::sum())();
               // [2, 3, 4]
        ```
        - 👍 Works super similarly to collections, but just accepts & returns normal arrays (no ->toArray()-ing necessary) 
        - 👍 Works super similarly to collections for everything else too!
        - 👎 Unfortunately it is an extra dependency (we don't have the native pipe operator yet https://wiki.php.net/rfc/pipe-operator-v2)

- You can add currying to any function, it isn't only limited to Vector built ins.
    - `Module::curry('explode')(',')('a,b,c')(PHP_INT_MAX)` `// ['a', 'b', 'c']`

## PHP Version Support
- 8.0+

## Install
```
composer require vector/core
```

## Show Me Some More Code

More automatic currying.
```php
$addOne = Arrays::map(Math::add(1));
$addOne([1, 2, 3]); // [2, 3, 4]
```

First class composition (Functional Pipelines).
```php
$addSix = Lambda::compose(Math::add(4), Math::add(2)); // (Or ::pipe for the opposite flow direction)
$addSix(4); // 10;
```

Pattern Matching (Maybe & Result monads included).
```php
Pattern::match([
    fn(Just $value) => fn ($unwrapped) => $unwrapped,
    fn(Nothing $value) => 'nothing',
])(Maybe::just('just')); // 'just'
```

Granular control flow (without try/catch).
```php
$errorHandler = function (Err $err) {
    return Pattern::match([
        function (QueryException $exception) {
            Log::info($exception);
            return response(404);
        },
        function (DBException $exception) {
            Log::error($exception);
            return response(500);
        },
    ]);
};

return Pattern::match([
    fn(Ok $value) => fn (User $user) => $user,
    $errorHandler
])(Result::from(fn() => User::findOrFail(1)));
```

Make your own modules with auto-curried methods
```php
use Vector\Core\Curry;
use Vector\Core\Module;

class MyModule
{
    use Module;
    
    #[Curry]
    protected static function myCurriedFunction($a, $b)
    {
        return $a + $b;
    }
}

```
