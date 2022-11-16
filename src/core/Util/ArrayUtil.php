<?php

namespace Core\Util;


class ArrayUtil
{
    public static function set(array &$array, $key, $value)
    {
        $keys = explode('.', $key);

        while(count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;
    }


    public static function get($array, $key, $default = null)
    {
        if (static::exists($array, $key)) {
            return $array[$key];
        }

        if (strpos($key, '.') !== false) {
            $array = static::findDot($array, $key);

            if (static::exists($array, $key)) {
                return $array[$key];
            }
        }

        return $default;
    }

    public static function has($array, $key)
    {
        if (static::exists($array, $key)) {
            return true;
        }

        if (strpos($key, '.') === false) {
            return false;
        }

        $array = static::findDot($array, $key);

        return static::exists($array, $key);
    }

    public static function exists($array, $key)
    {
        return array_key_exists($key, $array);
    }

    public static function findDot($array, $key)
    {
        $result = static::findFlatKey('.', $array, $key);

        return $result ? [$result['key'] => $result['value']] : [];
    }

    public static function findFlatKey($separator, $array, $key)
    {
        $keysPath = [];
        $result = null;

        if (strpos($key, $separator) !== false) {
            $keys = explode($separator, $key);
            $value = $array;

            while ($keys) {
                $k = array_shift($keys);

                if (!array_key_exists($k, $value)) {
                    break;
                }

                $value = $value[$k];
                $keysPath[] = $k;

                if ($key == implode($separator, $keysPath)) {
                    $result = [
                        'key' => $key,
                        'value' => $value
                    ];
                }

                // stop the search if the next value is not an array
                if (!is_array($value)) {
                    break;
                }
            }
        }

        return $result;
    }
}