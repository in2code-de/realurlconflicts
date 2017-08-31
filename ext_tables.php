<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(function () {

    /**
     * Include Backend Module
     */
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'In2code.realurlconflicts',
        'web',
        'm1',
        '',
        array(
            'Module' => 'conflicts,deleteCache'
        ),
        array(
            'access' => 'user,group',
            'icon' => 'EXT:realurlconflicts/Resources/Public/Icons/Module.svg',
            'labels' => 'LLL:EXT:realurlconflicts/Resources/Private/Language/locallang_mod.xlf',
        )
    );
});
