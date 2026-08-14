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

### v2.0.0 ###

Update project to Symfony 7 and set minimum PHP version to 8.2:
- `symfony/framework-bundle`, `symfony/http-client`, `symfony/yaml` to `^7.0`
- Declare `symfony/http-foundation` explicitly (used directly for
  `Request::METHOD_POST`, previously only available transitively)
- Drop the stale `ext-curl` requirement, unused since the HttpClient migration
  (v1.4.2)
- `PlatiniumExtension` now extends
  `Symfony\Component\DependencyInjection\Extension\Extension`, since the
  HttpKernel-namespaced one is deprecated as of Symfony 8.1
- `phpstan.neon`/`rector.php` updated for PHP 8.2 and the Symfony 7 rule set
- Fixed along the way, found via new regression coverage: `Configuration::getConfigTreeBuilder()`
  missing its native `TreeBuilder` return type (fatal error as soon as
  symfony/config enforces it), and a missing `symfony/yaml` dependency
  required by `PlatiniumExtension`'s `YamlFileLoader` but never declared
- Added regression tests for `PlatiniumClient`, `PlatiniumExtension`/DI
  container compilation, and `PlatiniumBundle`, previously untested

### v2.1.0 ###

Update project to Symfony 7.1: bump `symfony/framework-bundle`,
`symfony/http-client`, `symfony/http-foundation`, `symfony/yaml` and
`symfony/phpunit-bridge` to `^7.1`, target `SymfonySetList::SYMFONY_71` in
rector.php. No code changes needed: none of 7.1's deprecations touch this
bundle's surface (checked against UPGRADE-7.1.md and a real `composer
update`).
