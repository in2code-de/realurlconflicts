<?php
declare(strict_types=1);
namespace In2code\Realurlconflicts\ViewHelpers\Repository;

use In2code\Realurlconflicts\Utility\BackendUtility;
use In2code\Realurlconflicts\Utility\ConfigurationUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class GetPathFromUidViewHelper
 */
class GetPathFromUidViewHelper extends AbstractViewHelper
{

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('uid', 'int', 'Page identifier', true);
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return self::renderStatic(
            $this->arguments,
            $this->buildRenderChildrenClosure(),
            $this->renderingContext
        );
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $stopOnPid = (int)ConfigurationUtility::getConfiguration('stopOnPidForPagePath');
        $rootline = BackendUtility::getRootlineFromUid((int)$arguments['uid'], $stopOnPid);
        $path = '';
        foreach ($rootline as $pid) {
            $path .= ' / ' . self::getPageTitleFromUid($pid);
        }
        return trim($path, ' / ');
    }

    /**
     * @param int $uid
     * @return string
     */
    protected static function getPageTitleFromUid(int $uid): string
    {
        $title = '';
        $properties = BackendUtility::getPagePropertiesFromUid($uid);
        if (array_key_exists('title', $properties)) {
            $title = $properties['title'];
        }
        return $title;
    }
}
