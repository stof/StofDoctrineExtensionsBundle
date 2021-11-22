## 1.7.0 (2021-11-22)

Features:

* Added support for Symfony 6

Misc:

* Added a CI job running on PHP 8.1
* Renamed the main branch to `main`. Requiring `dev-master` explicitly will fail. Constraints `^1.7@dev` should be used to use the dev version matching a semver range instead, when necessary.

## 1.6.0 (2021-03-05)

Features:

* Added support for PHP 8

Removed:

* Dropped support for unmaintained versions of Symfony. The minimum Symfony versions are now 4.4 and 5.2.

## 1.5.0 (2020-09-30)

Features:

* Added support for `gedmo/doctrine-extensions` 3

## 1.4.0 (2020-03-30)

Features:

* Added support for Symfony 5

Removed:

* Dropped support for unmaintained versions of Symfony, and for Symfony 3.4. The minimum Symfony version is now 4.3
* Dropped support for unmaintained PHP versions.

## 1.3.0 (2017-12-24)

Features:

* Added support for Symfony 4
* Added autowiring support for `Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager`

Bugfixes:

* Fixed usage of loggable and soft-deleteable together to ensure soft-deletions are logged
* Removed the logic running on bundle boot to avoid overhead

Removed:

* Dropped support for unmaintained versions of Symfony

## 1.2.2 (2016-01-27)

* Added support for Symfony 3

## 1.2.1 (2015-08-12)

* Fixed the BlameListener

## 1.2.0 (2015-08-12)

Bugfixes:

* Fixed the handling of the directory validation of the uploadable extension
* Removed usage of APIs deprecated in Symfony 2.6+

Features:

* Marked the Gedmo extensions 2.4.0 release as supported (as well as any future 2.x release thanks to semver)
