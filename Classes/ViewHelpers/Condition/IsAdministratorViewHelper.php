<?php
declare(strict_types=1);
namespace In2code\Realurlconflicts\ViewHelpers\Condition;

use In2code\Realurlconflicts\Utility\BackendUserUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

/**
 * Class IsAllowedToEditViewHelper
 */
class IsAdministratorViewHelper extends AbstractConditionViewHelper
{

    /**
     * @param null $arguments
     * @return bool
     */
    protected static function evaluateCondition($arguments = null): bool
    {
        return BackendUserUtility::isAdministrator();
    }
}
