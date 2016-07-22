#! /bin/bash

echo "Building documentation..."
php ./bin/vector.php docs:generate \
    Vector\\Lib\\ArrayList \
    Vector\\Lib\\Lambda \
    Vector\\Lib\\Logic \
    Vector\\Lib\\Math \
    Vector\\Lib\\Object \
    Vector\\Lib\\Strings \
    Vector\\Control\\Applicative \
    Vector\\Control\\Functor \
    Vector\\Control\\Lens \
    Vector\\Control\\Monad