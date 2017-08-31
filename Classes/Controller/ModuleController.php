<?php
declare(strict_types=1);
namespace In2code\Realurlconflicts\Controller;

use In2code\Realurlconflicts\Domain\Repository\RealUrlRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class ModuleController
 */
class ModuleController extends ActionController
{

    /**
     * @return void
     */
    public function conflictsAction()
    {
        $realurlRepository = $this->objectManager->get(RealUrlRepository::class);
        $records = $realurlRepository->findAllDuplicates($this->getPid());
        $this->view->assign('records', $records);
        $this->view->assign('pid', $this->getPid());
    }

    /**
     * @return int
     */
    protected function getPid(): int
    {
        return (int)GeneralUtility::_GP('id');
    }
}
