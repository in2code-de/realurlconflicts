<?php
declare(strict_types=1);
namespace In2code\Realurlconflicts\ViewHelpers\Link;

use In2code\Realurlconflicts\Utility\BackendUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * Class EditPageViewHelper
 */
class EditPageViewHelper extends AbstractTagBasedViewHelper
{

    /**
     * @var string
     */
    protected $tagName = 'a';

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('pid', 'int', 'Page identifier', true);
        $this->registerArgument('title', 'string', 'Any title attribute value');
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $this->tag->addAttribute('href', BackendUtility::getEditUri('pages', $this->arguments['pid']));
        $this->tag->setContent($this->renderChildren());
        if ($this->arguments['title'] !== null) {
            $this->tag->addAttribute('title', $this->arguments['title']);
        }
        return $this->tag->render();
    }

    /**
     * @param int $pageIdentifier
     * @return string
     */
    protected function getUri(int $pageIdentifier): string
    {
        return '/index.php?id=' . $pageIdentifier;
    }
}
