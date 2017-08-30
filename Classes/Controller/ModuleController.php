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
        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($records, 'in2code: ' . __CLASS__ . ':' . __LINE__);
    }
}
