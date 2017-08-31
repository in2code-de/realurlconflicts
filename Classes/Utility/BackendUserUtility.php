<?php
declare(strict_types=1);
namespace In2code\Realurlconflicts\Utility;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

/**
 * Class BackendUserUtility
 */
class BackendUserUtility
{

    /**
     * Check if backend user is admin
     *
     * @return bool
     */
    public static function isAdministrator(): bool
    {
        if (isset(self::getBackendUserAuthentication()->user)) {
            return self::getBackendUserAuthentication()->user['admin'] === 1;
        }
        return false;
    }

    /**
     * @return BackendUserAuthentication
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public static function getBackendUserAuthentication()
    {
        return $GLOBALS['BE_USER'];
    }
}
