# Symfony 4 skeleton

## History

Originally, this was created as part of pairing sessions with a developer who had claimed that "TDD slows you down rather than helps" and that "you can always write the tests later". So we tried that, and we found that:

* Refactoring without tests is painful
* Writing tests every lunchtime for a week is _horrifying_.

Don't skip on TDD, folks.

Saying that, we came across some interesting ideas, so I turned it into a skeleton and an experiment to see if I can hit very high test coverage _after_ the main functionality has been written.

Spoiler: It is hard work. _Don't skip on TDD, folks._

## PHP 7.4
The project runs on [PHP 7.4][php74] or above. As not many distros have this by default in their repositories, this is provided in two ways:
- [a Vagrant box][vagrant] (Ubuntu bionic)
- [Official php docker images][docker_php]

The Vagrant box also has docker and [docker-compose][docker_compose].

## Test Strategy
The skeleton uses various test strategies - some chosen simply to try them out, others because they were the best fit.

* [behat][behat] is chosen to provide end to end testing of various _contexts_.
* [phpunit][phpunit] is provided for unit testing implementations.
* [phpspec][phpspec] is chosen for creating specs for domain logic, where it's limitations actually force development of business logic without resorting to implementation.
* [infection][infection] is used to provide AST mutation testing to ensure the unit tests cover the right things.

## Running tests

The main functionality is provided by a [Makefile](./Makefile). The principle commands:

```bash
make phpunit
make phpspec
make behat
make infection
```
These all run in order if you run the command `make test`.

[docker_php]: https://hub.docker.com/_/php/
[docker_compose]: https://docs.docker.com/compose/
[php74]: https://www.php.net/manual/en/migration74.php
[behat]: https://behat.org/en/latest/
[phpunit]: https://phpunit.de/
[phpspec]: https://www.phpspec.net/en/stable/
[infection]: https://infection.github.io/
[vagrant]: https://www.vagrantup.com/