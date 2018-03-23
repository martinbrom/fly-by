# Contributing to Fly-By

The following is a set of guidelines for contributing to Fly-By.
These are mostly guidelines, not rules.

## Reporting Bugs

When you are creating a bug report, please include as many details as possible. More information
helps us resolve issues faster.

## Styleguides

### Git Commit Messages

* Only use English
* Use the present tense ("Add feature" not "Added feature")
* Use the imperative mood ("Move file to..." not "Moves file to...")

### PHP Styleguide

* Prefer writing array with square brackets instead of using the word itself
  * `[]` instead of `array()`
* Use spaces after commas (unless separated by newlines)
* Prefer placing curly braces on the same line, except for when defining classes
  * `class ` instead of `class {`
  * `function index() {` instead of `function index()`
* Capitalize initialisms and acronyms in names, except for the first word, which
  should be lower-case:
  * `getURI` instead of `getUri`
  * `uriToOpen` instead of `URIToOpen`
