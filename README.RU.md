Enum Mapper [![In English](https://img.shields.io/badge/Switch_To-English-green.svg?style=flat-square)](./README.md)
===========

Введение
--------

Компонент обеспечивает эмуляцию поведения `ENUM` поля баз данных на уровне `PHP` посредством констант класса `PHP`.

Установка
---------

Откройте консоль и, перейдя в директорию проекта, выполните следующую команду для загрузки наиболее подходящей
стабильной версии этого компонента:
```text
    composer require adrenalinkin/enum-mapper
```
*Эта команда подразумевает что [Composer](https://getcomposer.org) установлен и доступен глобально.*

Пример использования
--------------------

### Создание маппера

Для начала создаем класс-маппер и наследуем абстрактный класс [AbstractEnumMapper](./Mapper/AbstractEnumMapper.php).
Вторым и завершающим этапом является определение набора констант со специальными префиксами:
 * **DB_** - содержит значение из базы данных.
 * **HUMAN_** - содержит значение в человеко-понятной форме.

Допустим у вас имеется необходимость хранить пол пользователей сайта. Тогда ваш класс будет иметь следующий вид:

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

### Применение

#### fromDbToHuman

Получение человеко-понятного представление на основе данных из базы данных:

```php
<?php

    $mapper        = new GenderMapper();
    $dbGenderValue = GenderMapper::DB_MALE; // 10
    $humanValue    = $mapper->fromDbToHuman($dbGenderValue);
```

Переменная `$humanValue` будет содержать значение `Male`.
**Примечание**: При попытке получить представление для незарегистрированного значения будет брошено исключение
`UndefinedMapValueException`.

#### fromHumanToDb

Получение значения для хранения в базе данных на основе человеко-понятного представления:

```php
<?php

    $mapper           = new GenderMapper();
    $humanGenderValue = GenderMapper::HUMAN_FEMALE; // Female
    $dbValue          = $mapper->fromHumanToDb($humanGenderValue);
```

Переменная `$dbValue` будет содержать значение `20`.
**Примечание**: При попытке получить представление для незарегистрированного значения будет брошено исключение
`UndefinedMapValueException`.

#### getMap

Получение полного списка соответствий значений из базы данных и их человеко-понятных значений:

```php
<?php

    $mapper = new GenderMapper();
    $map    = $mapper->getMap(); // [0 => 'Undefined', 10 => 'Male', 20 => 'Female']
```

### Использование констант

Всегда доступен стандартный вызов констант:

```php
<?php

    if (GenderMapper::DB_UNDEFINED === $maleFromForm) {
        throw new \Exception('Поле "Пол" обязательно для заполнения в этой форме');
    }
```

#### getAllowedDbValues и getAllowedHumanValues

Получение списка всех доступных значений для базы данных и аналогичный метод для получения доступных человеко-понятных
значений:

```php
<?php

    $mapper       = new GenderMapper();
    $allowedDb    = $mapper->getAllowedDbValues();    // [0, 10, 20]
    $allowedHuman = $mapper->getAllowedHumanValues(); // ['Undefined', 'Male', 'Female']

    // Исключение значений из возвращаемого результата
    $allowedDb    = $mapper->getAllowedDbValues([GenderMapper::DB_UNDEFINED]);       // [10, 20]
    $allowedHuman = $mapper->getAllowedHumanValues([GenderMapper::HUMAN_UNDEFINED]); // ['Male', 'Female']
```

#### getRandomDbValue и getRandomHumanValue

Получение случайного значения одного из доступных значений базы данных или человеко-понятного представления:

```php
<?php

    $mapper      = new GenderMapper();
    $randomDb    = $mapper->getRandomDbValue();    // 0 || 10 || 20
    $randomHuman = $mapper->getRandomHumanValue(); // Undefined || Male || Female

    // Исключение значений из возвращаемого результата
    $randomDb    = $mapper->getRandomDbValue([GenderMapper::DB_UNDEFINED]);       // 10 || 20
    $randomHuman = $mapper->getRandomHumanValue([GenderMapper::HUMAN_UNDEFINED]); // Male || Female
```

Лицензия
--------

[![license](https://img.shields.io/badge/License-MIT-green.svg?style=flat-square)](./LICENSE)
