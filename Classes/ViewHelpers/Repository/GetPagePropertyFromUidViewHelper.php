<?php
declare(strict_types=1);
namespace In2code\Realurlconflicts\ViewHelpers\Repository;

use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class GetPagePropertyFromUidViewHelper
 */
class GetPagePropertyFromUidViewHelper extends AbstractViewHelper
{

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('uid', 'int', 'Page identifier', true);
        $this->registerArgument('propertyName', 'string', 'Page property', true);
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
        $record = self::getPagePropertiesFromUid($arguments['uid']);
        if (array_key_exists($arguments['propertyName'], $record)) {
            return $record[$arguments['propertyName']];
        }
        return '';
    }

    /**
     * @param int $uid
     * @return array
     */
    protected static function getPagePropertiesFromUid(int $uid): array
    {
        return (array)BackendUtilityCore::getRecord('pages', $uid, '*');
    }
}
