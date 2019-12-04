# Symfony skeleton

## History

Originally, this was created as part of pairing sessions with a developer who had claimed that "TDD slows you down rather than helps" and that "you can always write the tests later". So we tried that, and we found that:

* Refactoring without tests is painful
* Writing tests every lunchtime for a week is horrifying.

Don't skip on TDD, folks.

Saying that, we came across some interesting ideas, so I turned it into a skeleton and an experiment to see if I can hit very high test coverage _after_ the main functionality has been written.

## PHP 7.4
The project runs on PHP 7.4 or above. As not many distros have this by default in their repositories, this is provided in two ways:
- a Vagrant box (Ubuntu bionic)
- Official php docker images

The Vagrant box also has docker and docker-compose.

## Test Strategy
The skeleton uses various test strategies - some chosen simply to try them out, others because they were the best fit.

* behat is chosen to provide end to end testing of various contexts
* phpunit is provided for unit testing implementations.
* phpspec is chosen for creating specs for domain logic, where it's limitations actually force development of business logic without resorting to implementation.