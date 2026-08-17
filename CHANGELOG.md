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
update`). Also ran rector with the new rule set applied: promoted
constructor properties, replaced `empty()` checks with explicit
type-matching comparisons, and dropped redundant `@param` docblock lines -
mechanical only, no behavior change.

### v2.2.0 ###

Update project to Symfony 7.2: bump `symfony/framework-bundle`,
`symfony/http-client`, `symfony/http-foundation`, `symfony/yaml` and
`symfony/phpunit-bridge` to `^7.2`. No code changes needed: none of 7.2's
deprecations touch this bundle's surface (checked against UPGRADE-7.2.md and
a real `composer update`). `phpstan.neon` unchanged (PHP floor still 8.2).
`rector.php` stays on `SymfonySetList::SYMFONY_71`, the latest available in
the `rector/rector` `^1.2` line - a `SYMFONY_72` set only exists starting
with Rector 2.x; ran rector anyway, nothing to apply.

### v2.3.0 ###

Update project to Symfony 7.3: bump `symfony/framework-bundle`,
`symfony/http-client`, `symfony/http-foundation`, `symfony/yaml` and
`symfony/phpunit-bridge` to `^7.3`. No code changes needed: none of 7.3's
deprecations touch this bundle's surface (checked against UPGRADE-7.3.md and
a real `composer update`). `phpstan.neon` unchanged (PHP floor still 8.2).
`rector.php` still on `SymfonySetList::SYMFONY_71`, same `rector/rector`
`^1.2` limitation as v2.2.0; ran rector anyway, nothing to apply.

### v2.4.0 ###

Update project to Symfony 7.4 (the next LTS release): bump
`symfony/framework-bundle`, `symfony/http-client`, `symfony/http-foundation`,
`symfony/yaml` and `symfony/phpunit-bridge` to `^7.4`. No code changes
needed: none of 7.4's deprecations touch this bundle's surface (checked
against UPGRADE-7.4.md and a real `composer update`) - notably
`ExtensionInterface::getXsdValidationBasePath()`/`getNamespace()` are
deprecated, but `PlatiniumExtension` doesn't override either. `phpstan.neon`
unchanged (PHP floor still 8.2).

Also upgraded `rector/rector` from `^1.2` to `^2.6` (and `phpstan/phpstan`
from `^1.12` to `^2.2`, a hard requirement of rector 2.6) to get past the
`SymfonySetList::SYMFONY_71` ceiling that's been a no-op every round since
v2.2.0. rector.php now targets `SymfonySetList::COMPOSER_BASED`, which gates
each rule by the actually-installed composer package version instead of a
hardcoded per-minor constant, covering Symfony 3.4 through 8.1 - no more
manual rector.php edits needed on future Symfony bumps. Ran the new set and
applied what it found: `declare(strict_types=1)` on the files it judged safe
to strict-type (it correctly skipped `PlatiniumClient.php` and
`PlatiniumNotifier.php`, which have pre-existing type-precision issues -
tracked separately, not fixed here), a couple of boolean/array-check
simplifications, and one test made to compile its container the same way
Symfony itself does during cache warmup (`compile(true)`).

### v3.0.0 ###

Update project to Symfony 8.0: bump `php` to `>=8.4` (Symfony 8.0's minimum
PHP version) and `symfony/framework-bundle`, `symfony/http-client`,
`symfony/http-foundation`, `symfony/yaml`, `symfony/phpunit-bridge` to
`^8.0`. `phpstan.neon` phpVersion to `80400`, `rector.php` targets `php84`.

Checked UPGRADE-8.0.md thoroughly since this is a major version bump: every
removed API (XML config/routing, `!tagged`,
`#[TaggedIterator]`/`#[TaggedLocator]`,
`ExtensionInterface::getXsdValidationBasePath()`/`getNamespace()`,
`RateLimiterFactory` autowiring, `amphp/http-client`, session/router/
validation config options, `TranslationUpdateCommand`, `WorkflowDumpCommand`,
`--show-arguments`) is something this bundle doesn't use - and unlike prior
rounds this isn't just a doc read: the test suite has already been
exercising symfony/dependency-injection and symfony/http-kernel resolved at
8.1.x transitively since v2.4.0, with zero deprecations the whole way. Ran
rector's `SymfonySetList::COMPOSER_BASED` set: nothing Symfony-specific
matched, only `AddTypeToConstRector` adding native types to two class
constants (a PHP 8.3+ feature now within reach).

Major version bump because the PHP floor moved, matching this bundle's own
precedent (v1.4.0/PHP 8.1, v2.0.0/PHP 8.2).

### v3.1.0 ###

Update project to Symfony 8.1: bump `symfony/framework-bundle`,
`symfony/http-client`, `symfony/http-foundation`, `symfony/yaml` and
`symfony/phpunit-bridge` to `^8.1`. No code changes needed: the one
deprecation with real teeth in UPGRADE-8.1.md -
`Symfony\Component\HttpKernel\DependencyInjection\Extension` deprecated
again - was already handled back in v2.0.0 when `PlatiniumExtension` was
switched to the DependencyInjection component's version. Everything else
(`BundleInterface`, `Bundle::registerCommands()`, `from_callable` option
deprecations, `#[Target]` autowiring) doesn't touch this bundle. No PHP
version change. Verified with a real `composer update`: test suite green, no
deprecations, phpstan clean, rector found nothing to apply.
