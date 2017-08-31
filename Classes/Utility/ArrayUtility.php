<?php
declare(strict_types=1);
namespace In2code\Realurlconflicts\Utility;

use TYPO3\CMS\Core\Utility\ArrayUtility as ArrayUtilityCore;

/**
 * Class ArrayUtility
 */
class ArrayUtility extends ArrayUtilityCore
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
