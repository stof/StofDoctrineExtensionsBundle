Upgrading from 1.0 to 1.1
=========================

This file describes the needed changes when upgrading from 1.0 to 1.1

### Bumped the requirements

The bundle now requires Symfony 2.1 and the 2.3 version of the Gedmo extensions
(which is the master branch at the time of this writing)

### Removed the duplicated entities.

The bundle no longer duplicates the entities provided by the extensions
to make the maintenance easier. You need to configure the mapping explicitly
for the extensions as DoctrineBundle cannot guess it.
See the updated documentation about registering the mapping for the way to
register them.
