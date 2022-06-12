<?php


namespace Piotr\Module\ProductBlocks\Enum;

use ReflectionClass;
use ReflectionException;

abstract class Enum
{
    /**
     * @var array
     */
    private static array $const = [];

    /**
     * @return array
     */
    public static function getConstants(): array
    {
        $calledClass = static::class;
        if (!array_key_exists($calledClass, self::$const)) {
            try {
                $reflect = new ReflectionClass($calledClass);
            } catch (ReflectionException $e) {
                return [];
            }
            self::$const[$calledClass] = $reflect->getConstants();
        }
        return self::$const[$calledClass];
    }

    public static function isValidName(string $name, bool $strict = false): bool
    {
        $constants = self::getConstants();

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys, true);
    }

    public static function isValidValue($value, bool $strict = true): bool
    {
        $values = array_values(self::getConstants());
        return in_array($value, $values, $strict);
    }

    public static function toString(): string
    {
        $result = "";
        foreach (self::$const[static::class] as $key => $const) {
            $result .= "$const - (" . trim(implode(" ", preg_split('/(?=[A-Z])/', $key))) . "), ";
        }
        return substr($result, 0, -2);
    }
}