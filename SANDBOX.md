# Symfony 4 skeleton

## History

Originally, this was created as part of pairing sessions with a developer who had claimed that "TDD slows you down rather than helps" and that "you can always write the tests later". So we tried that, and we found that:

* Refactoring without tests is painful
* Writing tests every lunchtime for a week is _horrifying_.

Don't skip on TDD, folks.

Saying that, we came across some interesting ideas, so I turned it into a skeleton and an experiment to see if I can hit very high test coverage _after_ the main functionality has been written.

Spoiler: It is hard work. _Don't skip on TDD, folks._

## Why all the code?
While a technical test is supposed to show the developer is aware of not [gold plating][gold_plating] a feature, technical tests are, by their nature, an opportunity to show your experience. Therefore:
- the heart of the technical test will _not_ be gold-plated.
- the development environment _will_ use a variety of different technologies to show various approaches and to have a pre-baked test harness.

### Structure
While this is a Symfony 4 app, the established directory structure has some limitations. More granularity is required when splitting business logic from framework implementation than one directory.

Additionally, as the default Symfony-generated kernel is not easily unit tested (and it's your code, so you should have _some_ tests around it), it has been replaced with a testable version. This sacrifices the highly-optimised version for testability and has not been benchmarked; be careful.

Beyond the standard `/vendor` directory, there are three main directories:
- `/application`: This is where the main code runs. It is separated into two directories in a hope to keep it aligned with Domain-Driven Design (DDD):
  - `src`: for the domain (business logic)
  - `app`: for the implementation
  - `lib`: An implementation of the Symfony kernel that is testable by mortals.
  
- `/tests`: this is where all the various tests exist. 

- `/tools`: Various tools (docker, ansible, shell scripts) for making a better life.

## PHP 7.4
The project runs on [PHP 7.4][php74] or above. As not many distros have this by default in their repositories, this is provided in two ways:
- [a Vagrant box][vagrant] ([Ubuntu bionic][bionic])
- [Official php docker images][docker_php]

The Vagrant box also has docker and [docker-compose][docker_compose]. It uses [ansible][ansible] to provision. If you don't have it, but you have Python, you can run the following:

```bash
pip3 install ansible
```

## Setup

The application will run with a simple:

```
make run
```

## Test Strategy
The skeleton uses various test strategies - some chosen simply to try them out, others because they were the best fit.

* [behat][behat] is chosen to provide end to end testing of various _contexts_.
* [phpunit][phpunit] is provided for unit testing implementations.
* [phpspec][phpspec] is chosen for creating specs for domain logic, where it's limitations actually force development of business logic without resorting to implementation.
* [infection][infection] is used to provide AST mutation testing to ensure the unit tests cover the right things.

## Running tests

The main functionality is provided by a [Makefile](./Makefile). The principle commands:

```bash
make phpunit # for phpunit tests
make phpspec # for specs
make behat # for behat features
make infection # to ensure unit tests are covering the core functions.
```

All of these run a shell script that detects if you have docker-compose locally, and if not, will run them inside vagrant.

These all run in order if you run the command `make test`. 

[docker_php]: https://hub.docker.com/_/php/
[docker_compose]: https://docs.docker.com/compose/
[php74]: https://www.php.net/manual/en/migration74.php
[behat]: https://behat.org/en/latest/
[phpunit]: https://phpunit.de/
[phpspec]: https://www.phpspec.net/en/stable/
[infection]: https://infection.github.io/
[vagrant]: https://www.vagrantup.com/
[ansible]: https://docs.ansible.com/
[bionic]: https://app.vagrantup.com/hashicorp/boxes/bionic64
[gold_plating]: https://dzone.com/articles/the-challenge-successful-design-v-gold-plating