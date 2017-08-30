<?php
namespace In2code\Realurlconflicts\Utility;

/**
 * Class ArrayUtility
 */
class ArrayUtility
{

    /**
     * @param array $paths
     * @return array
     */
    public static function filterArrayOnlyMultipleValues(array $paths): array
    {
        foreach (array_keys($paths) as $path) {
            if (count($paths[$path]) < 2) {
                unset($paths[$path]);
            }
        }
        return $paths;
    }
}
