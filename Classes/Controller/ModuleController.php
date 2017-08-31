<?php
declare(strict_types=1);
namespace In2code\Realurlconflicts\Controller;

use In2code\Realurlconflicts\Domain\Repository\RealUrlRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Class ModuleController
 */
class ModuleController extends ActionController
{

    /**
     * @var RealUrlRepository
     */
    protected $realurlRepository = null;

    /**
     * @return void
     */
    public function conflictsAction()
    {
        $paths = $this->realurlRepository->findAllDuplicates($this->getPid());
        $this->view->assignMultiple([
            'paths' => $paths,
            'pid' => $this->getPid()
        ]);
    }

    /**
     * @param string $path
     * @param int $pid
     * @return void
     */
    public function deleteCacheAction(string $path, int $pid)
    {
        $this->realurlRepository->deleteByPathAndPid($path, $pid);
        $this->addFlashMessage(LocalizationUtility::translate('action.deleteCache', 'Realurlconflicts', [$path, $pid]));
        $this->redirect('conflicts');
    }

    /**
     * @return int
     */
    protected function getPid(): int
    {
        return (int)GeneralUtility::_GP('id');
    }

    /**
     * @param RealUrlRepository $realurlRepository
     * @return void
     */
    public function injectRealurlRepository(RealUrlRepository $realurlRepository)
    {
        $this->realurlRepository = $realurlRepository;
    }
}
