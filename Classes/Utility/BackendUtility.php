<?php
declare(strict_types=1);
namespace In2code\Realurlconflicts\Utility;

use TYPO3\CMS\Backend\Routing\Exception\ResourceNotFoundException;
use TYPO3\CMS\Backend\Routing\Router;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
        return (array)self::getRecord('pages', $uid, '*', '', false);
    }

    /**
     * Create an URI to edit any record
     *
     * @param string $tableName
     * @param int $identifier
     * @param bool $addReturnUrl
     * @return string
     */
    public static function getEditUri($tableName, $identifier, $addReturnUrl = true)
    {
        $uriParameters = [
            'edit' => [
                $tableName => [
                    $identifier => 'edit'
                ]
            ]
        ];
        if ($addReturnUrl) {
            $uriParameters['returnUrl'] = self::getReturnUrl();
        }
        return BackendUtilityCore::getModuleUrl('record_edit', $uriParameters);
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
     * Get return URL from current request
     *
     * @return string
     */
    protected static function getReturnUrl()
    {
        return self::getModuleUrl(self::getModuleName(), self::getCurrentParameters());
    }

    /**
     * Get all GET/POST params without module name and token
     *
     * @param array $getParameters
     * @return array
     */
    public static function getCurrentParameters($getParameters = [])
    {
        if (empty($getParameters)) {
            $getParameters = GeneralUtility::_GET();
        }
        $parameters = [];
        $ignoreKeys = [
            'M',
            'moduleToken',
            'route',
            'token'
        ];
        foreach ($getParameters as $key => $value) {
            if (in_array($key, $ignoreKeys)) {
                continue;
            }
            $parameters[$key] = $value;
        }
        return $parameters;
    }

    /**
     * Get module name or route as fallback
     *
     * @return string
     */
    protected static function getModuleName()
    {
        $moduleName = 'web_layout';
        if (GeneralUtility::_GET('M') !== null) {
            $moduleName = (string)GeneralUtility::_GET('M');
        }
        if (GeneralUtility::_GET('route') !== null) {
            $routePath = (string)GeneralUtility::_GET('route');
            $router = GeneralUtility::makeInstance(Router::class);
            try {
                $route = $router->match($routePath);
                $moduleName = $route->getOption('_identifier');
            } catch (ResourceNotFoundException $exception) {
            }
        }
        return $moduleName;
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
