# Devel

This module provides libraries useful during development.

## Autoloader

The autoloader can be used during development to load dependend libraries from outside the
vendor library.

## Installation

It's **required** to install the Autoloader **after** loading the composer autoloader:

    if (<is-development-environment>) {
        \Octris\Devel\Autoloader::register([<root-paths>]);
    }
