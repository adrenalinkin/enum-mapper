Enum Mapper [![На Русском](https://img.shields.io/badge/Перейти_на-Русский-green.svg?style=flat-square)](./README.RU.md)
===========

Introduction
------------

Component provides easy way to emulate `ENUM` behaviour on the `PHP` layer instead database by constant usage.
Convert database value into human representation and vise versa.

Installation
------------

Open a command console, enter your project directory and execute the following command to download the latest stable
version of this component:
```text
    composer require adrenalinkin/enum-mapper
```
*This command requires you to have [Composer](https://getcomposer.org) install globally.*

Usage examples
--------------

### Mapper creation

For start create mapper-class and extend him from [AbstractEnumMapper](./Mapper/AbstractEnumMapper.php).
For second and last step - determine list of the constants with specific prefixes:
 * **DB_** - contains database values.
 * **HUMAN_** - contains humanized values.

Let's say we need to store user's gender. In that case we need to create mapper-class:

```php
<?php

use Linkin\Component\EnumMapper\Mapper\AbstractEnumMapper;

class GenderMapper extends AbstractEnumMapper
{
    const DB_UNDEFINED = 0;
    const DB_MALE      = 10;
    const DB_FEMALE    = 20;

    const HUMAN_UNDEFINED = 'Undefined';
    const HUMAN_MALE      = 'Male';
    const HUMAN_FEMALE    = 'Female';
}
```

### Usage

#### fromDbToHuman

Get humanized value by received database value:

```php
<?php

    $mapper        = new GenderMapper();
    $dbGenderValue = GenderMapper::DB_MALE; // 10
    $humanValue    = $mapper->fromDbToHuman($dbGenderValue);
```

Variable `$humanValue` will be contain `Male` value.
**Note**: When you will try to get value of the unregistered value will be throws `UndefinedMapValueException`.

#### fromHumanToDb

Get database value by received humanized value:

```php
<?php

    $mapper           = new GenderMapper();
    $humanGenderValue = GenderMapper::HUMAN_FEMALE; // Female
    $dbValue          = $mapper->fromHumanToDb($humanGenderValue);
```

Variable `$dbValue` will be contain `20` value.
**Note**: When you will try to get value of the unregistered value will be throws `UndefinedMapValueException`.

#### getMap

Get full list of the available pairs of the database and humanized values:

```php
<?php

    $mapper = new GenderMapper();
    $map    = $mapper->getMap(); // [0 => 'Undefined', 10 => 'Male', 20 => 'Female']
```

### Constant usage

All the time you available to use constant as is:

```php
<?php

    if (GenderMapper::DB_UNDEFINED === $maleFromForm) {
        throw new \Exception('Field "Gender" is required in this form');
    }
```

#### getAllowedDbValues and getAllowedHumanValues

Get list of the all available value for the database values or for the humanized values:

```php
<?php

    $mapper       = new GenderMapper();
    $allowedDb    = $mapper->getAllowedDbValues();    // [0, 10, 20]
    $allowedHuman = $mapper->getAllowedHumanValues(); // ['Undefined', 'Male', 'Female']

    // Exclude values from result
    $allowedDb    = $mapper->getAllowedDbValues([GenderMapper::DB_UNDEFINED]);       // [10, 20]
    $allowedHuman = $mapper->getAllowedHumanValues([GenderMapper::HUMAN_UNDEFINED]); // ['Male', 'Female']
```

#### getRandomDbValue и getRandomHumanValue

Get random database or humanized value:

```php
<?php

    $mapper      = new GenderMapper();
    $randomDb    = $mapper->getRandomDbValue();    // 0 || 10 || 20
    $randomHuman = $mapper->getRandomHumanValue(); // Undefined || Male || Female

    // Exclude values from result
    $randomDb    = $mapper->getRandomDbValue([GenderMapper::DB_UNDEFINED]);       // 10 || 20
    $randomHuman = $mapper->getRandomHumanValue([GenderMapper::HUMAN_UNDEFINED]); // Male || Female
```

License
-------

[![license](https://img.shields.io/badge/License-MIT-green.svg?style=flat-square)](./LICENSE)
