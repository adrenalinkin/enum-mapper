<?php

namespace Linkin\Component\EnumMapper\Mapper;

use Linkin\Component\EnumMapper\Exception\UndefinedMapValueException;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
abstract class AbstractEnumMapper
{
    const PREFIX_DB    = 'DB_';
    const PREFIX_HUMAN = 'HUMAN_';

    /**
     * @var string
     */
    private $className;

    /**
     * @var array
     */
    private $constants = [];

    /**
     * @param string|int $value
     * @param string     $prefixFrom
     *
     * @throws UndefinedMapValueException
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
     * @param int|string $value
     *
     * @throws UndefinedMapValueException
     *
     * @return int|string
     */
    public function fromDbToHuman($value)
    {
        return $this->convert($value, self::PREFIX_DB);
    }

    /**
     * @param int|string $value
     *
     * @throws UndefinedMapValueException
     *
     * @return int|string
     */
    public function fromHumanToDb($value)
    {
        return $this->convert($value, self::PREFIX_HUMAN);
    }

    /**
     * @param array $except
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
     * @param array $except
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
     * @param string $prefixFrom
     * @param string $constName
     *
     * @return int|string
     */
    protected function getAppropriateConstValue($prefixFrom, $constName)
    {
        $prefixTo  = $prefixFrom == self::PREFIX_DB ? self::PREFIX_HUMAN : self::PREFIX_DB;
        $count     = 1;
        $constName = str_replace($prefixFrom, $prefixTo, $constName, $count);

        return constant('static::' . $constName);
    }

    /**
     * @return string
     */
    private function getClassName()
    {
        if (null == $this->className) {
            $this->className = get_class($this);
        }

        return $this->className;
    }

    /**
     * @return array
     */
    private function getConstants()
    {
        if (empty($this->constants)) {
            $reflection      = new \ReflectionClass($this->getClassName());
            $this->constants = $reflection->getConstants();
        }

        return $this->constants;
    }

    /**
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
     * @param array $except
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
     * @param array $except
     *
     * @return string|int
     */
    public function getRandomHumanValue(array $except = [])
    {
        $values = $this->getAllowedHumanValues($except);

        shuffle($values);

        return array_shift($values);
    }
}
