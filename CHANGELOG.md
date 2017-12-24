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
