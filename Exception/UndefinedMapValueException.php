<?php

namespace Linkin\Component\EnumMapper\Exception;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class UndefinedMapValueException extends \RuntimeException
{
    /**
     * @param string     $className
     * @param string|int $value
     */
    public function __construct($className, $value)
    {
        $this->message = sprintf('Mapper "%s" does not contains mapping for the value "%s"', $className, $value);
    }
}
