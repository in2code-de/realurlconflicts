<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(
    function () {
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('realurl')) {
            /**
             * Include Backend Module
             */
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'In2code.realurlconflicts',
                'web',
                'm1',
                '',
                [
                    'Module' => 'conflicts,deleteCache,deleteCacheWithDeletedPages'
                ],
                [
                    'access' => 'user,group',
                    'icon' => 'EXT:realurlconflicts/Resources/Public/Icons/Module.svg',
                    'labels' => 'LLL:EXT:realurlconflicts/Resources/Private/Language/locallang_mod.xlf',
                ]
            );
        } else {
            \TYPO3\CMS\Core\Utility\GeneralUtility::sysLog(
                'Extension realurl not loaded - Module for realurlconflicts will not be included!',
                'realurlconflicts',
                \TYPO3\CMS\Core\Utility\GeneralUtility::SYSLOG_SEVERITY_WARNING
            );
        }
    }
);
