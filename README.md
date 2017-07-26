Enum Mapper
===========

Компонент позволит быстро быстро и удобно производить имитацию `ENUM` поля баз данных в слое `PHP`, для этого будет
достаточно унаследовать абстрактный класс **AbstractEnumMapper** и определить в наследнике набор констант со
специальными префиксами:

 * **DB_** - с этим префиксом константы будут содержать значение, которое должно быть в БД
 * **HUMAN_** - с этим префиксом константы будут содержать значение, которое должно быть показано пользователю.

### Пример использования

Допустим у вас имеется необходимость хранить пол. Тогда ваш класс будет иметь следующий вид:

```php
use Linkin\Component\EnumMapper\Mapper\AbstractEnumMapper;

class GenderMapper extends AbstractEnumMapper
{
    const DB_UNDEFINED = 0;
    const DB_MALE      = 1;
    const DB_FEMALE    = 2;

    const HUMAN_UNDEFINED = 'Undefined';
    const HUMAN_MALE      = 'Male';
    const HUMAN_FEMALE    = 'Female';
}
```

При необходимости вы можете получить человеко-понятное представление на основе данных из базы данных:

```php
    $humanValue = $mapper->fromDbToHuman($dbGenderValue);
```

А также осуществить такое же преобразование в обратном порядке:

```php
    $dbValue = $mapper->fromHumanToDb($humanGenderValue);
```

Если вы попытаетесь получить представление для не зарегистрированного значение, то будет брошено исключение
`UndefinedMapValueException`.

Еще вы имеете возможность получить полный список соответсвия значения из базы данных и их человеко-понятных значений:

```php
    $map = $mapper->getMap();
```

Конечно не стоит забывать и о том, что вы всегда можете использовать константы для сравнения:

```php
    if (GenderMapper::DB_UNDEFINED == $maleFromForm) {
        throw new \Exception('Поле "Пол" обязательно для заполнения в этой форме');
    }
```

В арсенале абстрактного класса есть еще несколько полезных методов, таких как получение списка всех доступных значений
для базы данных и аналогичный метод для получения доступных человеко-понятных значений:

```php
    $allowedDb    = $mapper->getAllowedDbValues();
    $allowedHuman = $mapper->getAllowedHumanValues();
```

Имеется возможность исключить некоторые значения, если передать список исключений в качетсве массива в методы:

```php
    $allowedDb    = $mapper->getAllowedDbValues([GenderMapper::DB_UNDEFINED]);
    $allowedHuman = $mapper->getAllowedHumanValues([GenderMapper::HUMAN_UNDEFINED]);
```

И также, при необходимости, можно получить случайное значение одного из доступных значений базы данных или
человеко-понятного представления:

```php
    $randomDb    = $mapper->getRandomDbValue();
    $randomHuman = $mapper->getRandomHumanValue();
```

Аналогично получению списка доступных значений можно исключить не желательные значения:

```php
    $randomDb    = $mapper->getRandomDbValue([GenderMapper::DB_UNDEFINED]);
    $randomHuman = $mapper->getRandomHumanValue([GenderMapper::HUMAN_UNDEFINED]);
```
