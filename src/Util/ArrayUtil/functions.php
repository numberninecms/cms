<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Util\ArrayUtil;

use BadFunctionCallException;

/**
 * Acts like array_merge_recursive except that scalars are correctly overridden
 * @param array $array1
 * @param array $array2
 * @return array
 * @link https://stackoverflow.com/a/36366886/1159013
 */
function array_merge_recursive_fixed(array $array1, array $array2): array
{
    foreach ($array2 as $key => $value) {
        if (isset($array1[$key])) {
            if (!is_array($array1[$key])) {
                $array1[$key] = $value;
            } elseif (array_keys($array1[$key]) === range(0, count($array1[$key]) - 1)) {
                $array1[$key] = array_unique(array_merge($array1[$key], $value));
            } else {
                $array1[$key] = array_merge_recursive_fixed($array1[$key], $value);
            }
        } else {
            $array1[$key] = $value;
        }
    }

    return $array1;
}


/**
 * Recursively merge arrays.
 *
 * Merge two arrays as array_merge_recursive do, but instead of converting values to arrays when keys are same
 * replaces value from first array with value from second
 *
 * @param array $first
 * @param array $second
 *
 * @return array
 */
function array_merge_recursive_distinct(array $first, array $second): array
{
    foreach ($second as $index => $value) {
        if (is_int($index)) {
            $first[] = $value;
        } elseif (!array_key_exists($index, $first)) {
            $first[$index] = $value;
        } elseif (is_array($value)) {
            if (is_array($first[$index])) {
                $first[$index] = array_merge_recursive_distinct($first[$index], $value);
            } else {
                $first[$index] = $value;
            }
        } else {
            $first[$index] = $value;
        }
    }

    return $first;
}

/**
 * Push a value in a nested array that doesn't necessarily exist
 *
 * Usage :
 *
 * with `$array = []`
 *
 * and `$value = 3`
 *          ArrayTool::arrayPushDeep($array, $value, 'key1', 2, 'key3');
 * results in:
 *          ['key1' => [2 => 'key3' => [3]]]
 *
 * @param array $array
 * @param mixed $value
 */
function array_push_deep(array &$array, $value): void
{
    if (func_num_args() < 3) {
        throw new BadFunctionCallException('Not enough parameters!');
    }

    $args = func_get_args();
    $currentArray = &$array;

    for ($i = 2, $iMax = func_num_args(); $i < $iMax; $i++) {
        if (!array_key_exists($args[$i], $currentArray)) {
            $currentArray[$args[$i]] = [];
        }

        $currentArray = &$currentArray[$args[$i]];
    }

    $currentArray[] = $value;
    $currentArray = array_unique($currentArray);
}

/**
 * Implode an associative array.
 * If a value is an array, its values are imploded with pipe as separator.
 *
 * @param array $array
 * @param string $glue
 * @param string $keyValueSeparator
 * @param string $keyWrapper
 * @param string $valueWrapper
 * @return string
 */
function array_implode_associative(array $array, string $glue = ';', string $keyValueSeparator = '=', string $keyWrapper = '', string $valueWrapper = ''): string
{
    return implode(
        $glue,
        array_map(
            static function ($value, $key) use ($keyValueSeparator, $keyWrapper, $valueWrapper) {
                if (is_array($value)) {
                    $value = implode('|', $value);
                }

                return $keyWrapper . $key . $keyWrapper . $keyValueSeparator . $valueWrapper . $value . $valueWrapper;
            },
            $array,
            array_keys($array)
        )
    );
}

/**
 * Sets a value or formatted value associated to a key to an array, only if the value exists
 * @param array $array
 * @param string $key
 * @param mixed $value
 * @param mixed|null $formattedValue
 * @param bool $strictNull
 */
function array_set_if_value_exists(array &$array, string $key, $value, $formattedValue = null, bool $strictNull = false): void
{
    if (($strictNull && $value !== null) || (!$strictNull && $value)) {
        $array[$key] = $formattedValue ?? $value;
    }
}

/**
 * Checks if an array is an associative array
 * @param array $array
 * @return bool
 */
function is_associative_array(array $array): bool
{
    if ($array === []) {
        return false;
    }

    return array_values($array) !== $array;
}

/**
 * @param array $array
 * @param string $childrenKey
 * @return int
 * @link https://stackoverflow.com/a/262944/1159013
 */
function array_depth(array $array, string $childrenKey = 'children'): int
{
    $maxDepth = 1;

    foreach ($array as $value) {
        if (!empty($value[$childrenKey])) {
            $depth = array_depth($value[$childrenKey]) + 1;

            if ($depth > $maxDepth) {
                $maxDepth = $depth;
            }
        }
    }

    return $maxDepth;
}

/**
 * Checks if all values of an array exists in another array
 * @param array $needles
 * @param array $haystack
 * @return bool
 */
function in_array_all(array $needles, array $haystack): bool
{
    return empty(array_diff($needles, $haystack));
}

/**
 * Checks if any of the values of an array exists in another array
 * @param array $needles
 * @param array $haystack
 * @return bool
 */
function in_array_any(array $needles, array $haystack): bool
{
    return !empty(array_intersect($needles, $haystack));
}

/**
 * Apply array_map recursively if the given $childrenKey exists
 * @param callable $callable
 * @param array $array
 * @param string $childrenKey
 * @return array
 */
function array_map_recursive(callable $callable, array $array, string $childrenKey = 'children'): array
{
    return array_map(
        static function ($item) use ($callable, $childrenKey) {
            if (array_key_exists($childrenKey, $item)) {
                $item[$childrenKey] = array_map_recursive($callable, $item[$childrenKey], $childrenKey);
            }

            return $callable($item);
        },
        $array
    );
}

function unset_recursive(array &$array, string $keyToUnset): void
{
    unset($array[$keyToUnset]);

    foreach ($array as &$value) {
        if (is_array($value)) {
            unset_recursive($value, $keyToUnset);
        }
    }
}
