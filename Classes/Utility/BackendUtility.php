<?php
declare(strict_types=1);
namespace In2code\Realurlconflicts\Utility;

use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;

/**
 * Class BackendUserUtility
 */
class BackendUtility extends BackendUtilityCore
{

    /**
     * @param int $uid
     * @return array
     */
    public static function getPagePropertiesFromUid(int $uid): array
    {
        return (array)self::getRecord('pages', $uid, '*');
    }

    /**
     * @param int $uid
     * @param int $stopUid Stop on this uid (this uid is also excluded)
     * @return array
     */
    public static function getRootlineFromUid(int $uid, int $stopUid = 0): array
    {
        $rootline = [$uid];
        $rootline = self::getRootlineRecursive($uid, $rootline, $stopUid);
        $rootline = array_reverse($rootline);
        return $rootline;
    }

    /**
     * Get array with current and parent page uids from startPid
     *
     * @param int $uid
     * @param array $rootline
     * @param int $stopUid
     * @return array
     */
    protected static function getRootlineRecursive(int $uid, array $rootline, int $stopUid = 0): array
    {
        if ($uid !== $stopUid && $uid > 0) {
            $properties = self::getPagePropertiesFromUid($uid);
            $pid = (int)$properties['pid'];
            if ($pid !== $stopUid) {
                $rootline[] = $pid;
                $rootline = self::getRootlineRecursive($pid, $rootline, $stopUid);
            }
        }
        return $rootline;
    }
}
