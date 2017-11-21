<?php

/*
 * This file is part of the EnumMapper component package.
 *
 * (c) Viktor Linkin <adrenalinkin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
