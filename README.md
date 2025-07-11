# PHP Webshell

## Description

This repository offers a POST-based PHP Web Shell to be used in conjunction with the
[python client](https://github.com/david-gilarranz016/python-client) as part of the 
thesis for the Software Engineering and Information Systems master's degree.

Both the shell and the client are not designed to be used on their own, but to be customized
and generated by the [Web Shell generator](https://github.com/david-gilarranz016/wonka-generator/).
As such, usage of the shell in this repository by end users is **strongly discouraged**, since
it is only meant to feed the generator.

## Instructions for Users

Even if the usage of this shell as-is is strongly discouraged, it is nonetheless possible to do so.
If the user decides to go in this direction, they have two options:

1. Use the repository as-is.
2. Manually craft the target Web Shell.

In the first case, both the `index.php` file and the `src` directory will need to be fully uploaded
to the target server, making it highly impractical, if not impossible in a real scenario.

If the user insists on using the contents of this repository instead of the intended generator, the
second approach is more suitable for real-world usage. In this case, the user will need to replace
all `include_once` statements with the actual content of the files.

Furthermore, it is strongly recommended that the values for the `$key` (`index.php:67`) as well as the
initial nonce (`index.php:64`) be changed to a random value. Furthermore, if the user intends to
target a server other than the local host, it may be useful to modify or delete the IP whitelist 
(`index.php:63`).

## Instructions for Developers

The project includes a set of unit tests in order to verify the quality of the developed shell. It is
strongly recommended to keep growing the test base if any modifications are to be made, as well as
running the existent tests to verify that the changes introduced have not broken the code.

In order to run the unit tests, it is first imperative to install the testing dependencies specified
in the `compose.json` file. To do so, the following command can be used:

```bash
composer install
```

Once the required dependencies have been installed, the developer can run the unit tests either by
making use of the included `Makefile` or running `phpunit` directly.

```bash
# Using the Makefile
make test

# Running phpunit directly
./vendor/bin/phpunit
```
