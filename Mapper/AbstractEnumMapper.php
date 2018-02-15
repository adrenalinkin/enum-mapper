<?php

/*
 * This file is part of the EnumMapper component package.
 *
 * (c) Viktor Linkin <adrenalinkin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Linkin\Component\EnumMapper\Mapper;

use Linkin\Component\EnumMapper\Exception\UndefinedMapValueException;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
abstract class AbstractEnumMapper
{
    /**
     * Constant prefix for the database values
     */
    const PREFIX_DB = 'DB_';

    /**
     * Constant prefix for the humanized value
     */
    const PREFIX_HUMAN = 'HUMAN_';

    /**
     * Name of the current class
     *
     * @var string
     */
    private $className;

    /**
     * List of the constants of the current class in the 'key' => 'value' pairs.
     * The 'key' contains constant name and 'value' contains constant value.
     *
     * @var array
     */
    private $constants = [];

    /**
     * Returns humanized value by received database value
     *
     * @param int|string $value Database value
     *
     * @throws UndefinedMapValueException When received database value does not exists
     *
     * @return int|string Humanized value
     */
    public function fromDbToHuman($value)
    {
        return $this->convert($value, self::PREFIX_DB);
    }

    /**
     * Returns database value by received humanized value
     *
     * @param int|string $value Humanized value
     *
     * @throws UndefinedMapValueException When received humanized value does not exists
     *
     * @return int|string Database value
     */
    public function fromHumanToDb($value)
    {
        return $this->convert($value, self::PREFIX_HUMAN);
    }

    /**
     * Returns list of the all registered database values
     *
     * @param array $except List of the database values which should be excluded
     *
     * @return array
     */
    public function getAllowedDbValues(array $except = [])
    {
        $map      = $this->getMap();
        $dbValues = array_diff(array_keys($map), $except);

        return $dbValues;
    }

    /**
     * Returns list of the all registered humanized values
     *
     * @param array $except List of the humanized values which should be excluded
     *
     * @return array
     */
    public function getAllowedHumanValues(array $except = [])
    {
        $map         = $this->getMap();
        $humanValues = array_diff(array_values($map), $except);

        return $humanValues;
    }

    /**
     * Returns map of the all registered values in the 'key' => 'value' pairs.
     * The 'key' equal to database value and the 'value' equal to humanized value.
     *
     * @return array
     */
    public function getMap()
    {
        $result = [];

        foreach ($this->getConstants() as $dbName => $dbValue) {
            if (0 === strpos($dbName, self::PREFIX_DB)) {
                $result[$dbValue] = $this->getAppropriateConstValue(self::PREFIX_DB, $dbName);
            }
        }

        return $result;
    }

    /**
     * Returns random database value
     *
     * @param array $except List of the database values which should be excluded
     *
     * @return string|int
     */
    public function getRandomDbValue(array $except = [])
    {
        $values = $this->getAllowedDbValues($except);

        shuffle($values);

        return array_shift($values);
    }

    /**
     * Returns random humanized value
     *
     * @param array $except List of the humanized values which should be excluded
     *
     * @return string|int
     */
    public function getRandomHumanValue(array $except = [])
    {
        $values = $this->getAllowedHumanValues($except);

        shuffle($values);

        return array_shift($values);
    }

    /**
     * Returns appropriated pair value
     *
     * @param string $prefixFrom Constant prefix  which determine what the value should be returns
     *                           put self::PREFIX_DB to get appropriated humanized value
     *                           or self::PREFIX_HUMAN to get appropriated database value
     * @param string $constName  Constant name which should be processed
     *
     * @return int|string
     */
    protected function getAppropriateConstValue($prefixFrom, $constName)
    {
        $prefixTo  = $prefixFrom === self::PREFIX_DB ? self::PREFIX_HUMAN : self::PREFIX_DB;
        $count     = 1;
        $constName = str_replace($prefixFrom, $prefixTo, $constName, $count);

        return constant('static::'.$constName);
    }

    /**
     * Returns appropriated value by received origin value. Humanized value by received database value and vise versa.
     *
     * @param string|int $value      Value for convert
     * @param string     $prefixFrom Constant prefix  which determine what the value should be returns
     *                               put self::PREFIX_DB to get appropriated humanized value
     *                               or self::PREFIX_HUMAN to get appropriated database value
     *
     * @throws UndefinedMapValueException When received value does not exists
     *
     * @return string|int
     */
    private function convert($value, $prefixFrom)
    {
        foreach ($this->getConstants() as $constName => $constValue) {
            // process constant if she's does start from reserved prefix and values was equals
            if (0 === strpos($constName, $prefixFrom) && $value === $constValue) {
                return $this->getAppropriateConstValue($prefixFrom, $constName);
            }
        }

        throw new UndefinedMapValueException($this->getClassName(), $value);
    }

    /**
     * Returns current class name
     *
     * @return string
     */
    private function getClassName()
    {
        if (null === $this->className) {
            $this->className = get_class($this);
        }

        return $this->className;
    }

    /**
     * Returns list of the all available constants of the current class
     *
     * @return array
     */
    private function getConstants()
    {
        if (empty($this->constants)) {
            try {
                $reflection      = new \ReflectionClass($this->getClassName());
                $this->constants = $reflection->getConstants();
            } catch (\ReflectionException $e) {
                $this->constants = [];
            }
        }

        return $this->constants;
    }
}
