<?php
namespace In2code\Realurlconflicts\Controller;

use In2code\Realurlconflicts\Domain\Repository\RealUrlRepository;
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
        $records = $realurlRepository->findAllDuplicates();
        $this->view->assign('records', $records);
    }
}
