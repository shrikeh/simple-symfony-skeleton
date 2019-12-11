Feature: So that I can deliver quality
  As a Symfony developer
  I want my app to be a well-tested, message-bus driven, Symfony application.

  Scenario: Fire a test message
    Given that I have the message "Hello behat!"
    When I send this message
    Then it is received.