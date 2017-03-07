BC GitHub Stars
===============

This bundle implements a solution to provide Symfony commands to search and import an GitHub repository data and populate a local database repository.


Version
=======

* The current version of BC GitHub Stars is 0.1.4

* Last Major update: February 24, 2017


Copyright
=========

* BC GitHub Stars is copyright 1999 - 2017 Brookins Consulting

* See: [COPYRIGHT.md](COPYRIGHT.md) for more information on the terms of the copyright and license


License
=======

Bc GitHub Stars is licensed under the GNU General Public License.

The complete license agreement is included in the [LICENSE](LICENSE) file.

Bc GitHub Stars is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License or at your
option a later version.

Bc GitHub Stars is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

The GNU GPL gives you the right to use, modify and redistribute
Bc GitHub Stars under certain conditions. The GNU GPL license
is distributed with the software, see the file [LICENSE](LICENSE).

It is also available at [http://www.gnu.org/licenses/gpl.txt](http://www.gnu.org/licenses/gpl.txt)

You should have received a copy of the GNU General Public License
along with Bc GitHub Stars in in the [LICENSE](LICENSE) file.  

If not, see [http://www.gnu.org/licenses/](http://www.gnu.org/licenses/).

Using Bc GitHub Stars under the terms of the GNU GPL is free (as in freedom).

For more information or questions please contact: license@brookinsconsulting.com


Requirements
============

The following requirements exists for using BC GitHub Stars bundle:


### Symfony version

* Make sure you use Symfony version 2.8+ (required) or higher.

* Designed and tested with Symfony 3.2.x


### PHP version

* Make sure you have PHP 7.1 or higher.


Features
========

### Commands

This solution provides the following symfony commands

* Command: `bc:gs:import`

### Services

* Services to assist in the search, import and update of github repositories

    * GitHubStars
    * GitHubStarsComand
    * GitHubStarsController
    * GitHubRepositoryModel

### Dependencies

* This solution does not depend on eZ Platform in any way
* This solution depends on symfony/symfony
* This solution depends on doctrine/orm and doctrine/doctrine-bundle
* This solution depends on knpLabs/php-github-api
* This solution depends on php-http/guzzle6-adapter
* This solution depends on jms/di-extra-bundle
* This solution depends on brookinsconsulting/bcknockoutjsbundle
* This solution depends on brookinsconsulting/github-api-bundle
* This solution depends on symfony/assetic-bundle
* This solution depends on braincrafted/bootstrap-bundle
* This solution depends on twbs/bootstrap
* This solution depends on jquery/jquery
* This solution depends on knplabs/knp-paginator-bundle
* This solution depends on knplabs/knp-menu-bundle

These dependencies are documented in greater detail within the bundle's composer.json file

Use case requirements
=====================

This solution was created to provide for a simple solution to importing and displaying github php repositories with a variable criteria of stars


Use case documentation
======================

https://developer.github.com/v3/search/
https://developer.github.com/v3/search/#search-repositories


Installation
============

### Bundle Installation via Composer

Run the following command from your project root to install the bundle:

    bash$ composer require brookinsconsulting/bcgithubstarsbundle dev-master;


### Bundle Activation

Within file `app/AppKernel.php` method `registerBundles` add the following into the `$bundles = array(` variable definition.

    // Brookins Consulting : BcGitHubStarsBundle Requirements
    new Maxikg\GithubApiBundle\GithubApiBundle(),
    new JMS\DiExtraBundle\JMSDiExtraBundle($this),
    new JMS\AopBundle\JMSAopBundle(),
    new BrookinsConsulting\BcKnockoutJSBundle\BcKnockoutJSBundle(),
    new Symfony\Bundle\AsseticBundle\AsseticBundle(),
    new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
    new Knp\Bundle\MenuBundle\KnpMenuBundle(),
    new Braincrafted\Bundle\BootstrapBundle\BraincraftedBootstrapBundle(),
    new BrookinsConsulting\BcGitHubStarsBundle\BcGitHubStarsBundle()


### Clear the caches

Clear Symfony caches (Required).

    php bin/console cache:clear;

### Create Database

    php bin/console doctrine:database:create;

### Create Database Schema

    php bin/console doctrine:schema:create;

### Route Installation

Edit your ```app/config/routing.yml``` file and add the following code to import this bundle's routes

    app_githubstars:
        resource: "@BcGitHubStarsBundle/Resources/config/routing.yml"

### Asset Installation

    php bin/console assets:install web --symlink --relative

### Assetic Installation

    php bin/console assetic:dump --env=prod


Parameter Customization
===================================

## Supported Parameters

Note: Currently no parameters are used or required at this time. The following exists for future expansion and refactoring.

Please review `Resources/config/githubstars.yml` and `Resources/config/services.yml` for the default parameters supported.


Usage
=====

The solution is configured to work virtually by default once properly installed.
    
### Running the import command

To import the repository data from GitHub run the following command and you will be prompted for required arguments

    php -d memory_limit=-1 bin/console bc:gs:import;

#### Running the create command with shell arguments

For repeated usage of the same input you can run the script more quickly using shell arguments. Simply run

    php -d memory_limit=-1 bin/console bc:gs:import -v --token=<github-token-string> --starsQuery=">500" --iterationStore --update

#### starsQuery Shell Arguement Input Syntax / Format

The starsQuery shell argument input format / syntax mirrors the github reset api syntax. Learn more about this syntax from the available github documentation: [https://help.github.com/articles/searching-repositories/#search-based-on-the-number-of-stars-a-repository-has](https://help.github.com/articles/searching-repositories/#search-based-on-the-number-of-stars-a-repository-has) and [https://help.github.com/articles/search-syntax/](https://help.github.com/articles/search-syntax/).

#### iterationStore Shell Arguement

This argument while optional greatly increases the speed in which records are populated into the database and is recommended for queries which larger result sets. This ensures if the command exits for any reason manual or otherwise at least some data is stored before it exits.

#### update Shell Arguement

This argement is optional and ensures that existing local database records are updated durring the process. This is helpful when running the command more than once and ensures that the repository meta data including the lastPushDate field is updated.


Usage : Website UI
=====

The solution is configured to work virtually by default once properly installed.
    
### Views available

* http://localhost/
* http://localhost/bcgithubstars/about
* http://localhost/repository/list
* http://localhost/repository/1

To use the available views simply use your web browser to navigate to them.


Notice
======

The tools provided by this bundle are currently very similar to the tools provided by GitHub.com

* https://github.com/trending/php

* https://github.com/search?o=desc&q=language%3APHP+stars%3A%3E%3D50&s=stars&type=Repositories&utf8=%E2%9C%93


Testing
=====

The solution is configured to work once properly installed and configured.

Note: At the time of writing xss testing has not been implemented nor proper unit testing.


Troubleshooting
===============

### Read the FAQ

Some problems are more common than others. The most common ones are listed in the the [Resources/doc/FAQ.md](Resources/doc/FAQ.md)


### Support

If you have find any problems not handled by this document or the FAQ you can contact Brookins Consulting through the support system: [http://brookinsconsulting.com/contact](http://brookinsconsulting.com/contact)

