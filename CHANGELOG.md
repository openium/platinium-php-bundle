CHANGELOG
=========

### 1.0 ###

First release of the Platinium Symfony Bundle

### v1.1.0 ###

Add langNotIn parameter

### v1.1.1 ###

Notifer correction for langNotIn, readme update

### v1.1.2 ###

service injection correction

### v1.1.3 ###

change notifier service to add some push options

### v1.1.4 ###

update readme
Correction newsstand false/true -> 0/1

### v1.1.5 ###

Correction platinium header name
improve exception message, comments, update composer.json description and add ext php,
remove unused var in Client and improve parseHttpHeaders method

### v1.1.6 ###

Update required fields from platinium response

### v1.2.0 ###

Update project to Symfony 4.4
And set minimum PHP version to 7.2

### v1.3.0 ###

Update project to Symfony 5.0

### v1.3.1 ### 

Update project to Symfony 5.1

### v1.3.2 ### 

Update project to Symfony 5.2

### v1.3.3 ### 

Update project to Symfony 5.3

### v1.3.4 ### 

Update project to Symfony 5.4

### v1.4.0 ###

Update project to Symfony 6
And set minimum PHP version to 8.1

### v1.4.1 ### 

Fix types and simplify PlatiniumPushInformation. Also make tolerance nullable

### v1.4.2 ### 

Use Symfony HttpClient instead of CURL

### v1.4.3 ###

Fix `openium_platinium.client` service argument: it was wrongly wired to
`%kernel.environment%` instead of `HttpClientInterface` after the v1.4.2 HttpClient
migration

### Unreleased ###

Preparation work for Symfony 7 compatibility (see `SYMFONY_7_UPGRADE_PLAN.md`):
- Fix `Configuration::getConfigTreeBuilder()` missing native `TreeBuilder` return
  type (fatal error as soon as symfony/config enforces it)
- Add missing `symfony/yaml` dependency, required by `PlatiniumExtension`'s
  `YamlFileLoader` but never declared
- Fix `phpstan.neon` stale `phpVersion` (was still 7.2) that silently broke static
  analysis on the PHP 8.1 codebase
- Add regression tests for `PlatiniumClient`, `PlatiniumExtension`/DI container
  compilation, and `PlatiniumBundle`, previously untested
