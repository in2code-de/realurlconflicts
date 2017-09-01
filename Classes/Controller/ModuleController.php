<?php
declare(strict_types=1);
namespace In2code\Realurlconflicts\Controller;

use In2code\Realurlconflicts\Domain\Repository\RealurlPathDataRepository;
use In2code\Realurlconflicts\Domain\Repository\RealurlUrlDataRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Class ModuleController
 */
class ModuleController extends ActionController
{

    /**
     * @var RealurlPathDataRepository
     */
    protected $realurlPathDataRepository = null;

    /**
     * @var RealurlUrlDataRepository
     */
    protected $realurlUrlDataRepository = null;

    /**
     * List of all conflicts
     *
     * @return void
     */
    public function conflictsAction()
    {
        $this->view->assignMultiple([
            'paths' => $this->realurlPathDataRepository->findAllDuplicates($this->getPid()),
            'pid' => $this->getPid(),
            'hasCachingEntriesFromDeletedPages' => $this->realurlPathDataRepository->hasCachingEntriesFromDeletedPages()
        ]);
    }

    /**
     * @param string $path
     * @param int $pid
     * @return void
     */
    public function deleteCacheAction(string $path, int $pid)
    {
        $this->realurlPathDataRepository->deleteByPathAndPid($path, $pid);
        $this->realurlUrlDataRepository->deleteByPathAndPid($path, $pid);
        $this->addFlashMessage(LocalizationUtility::translate('action.deleteCache', 'Realurlconflicts', [$path, $pid]));
        $this->redirect('conflicts');
    }

    /**
     * @return void
     */
    public function deleteCacheWithDeletedPagesAction()
    {
        $this->realurlPathDataRepository->deleteWithDeletedPages();
        $this->realurlUrlDataRepository->deleteWithDeletedPages();
        $this->addFlashMessage(LocalizationUtility::translate('action.deleteCacheDeletedPages', 'Realurlconflicts'));
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
     * @param RealurlPathDataRepository $realurlPathDataRepository
     * @return void
     */
    public function injectRealurlPathDataRepository(RealurlPathDataRepository $realurlPathDataRepository)
    {
        $this->realurlPathDataRepository = $realurlPathDataRepository;
    }

    /**
     * @param RealurlUrlDataRepository $realurlUrlDataRepository
     * @return void
     */
    public function injectRealurlUrlDataRepository(RealurlUrlDataRepository $realurlUrlDataRepository)
    {
        $this->realurlUrlDataRepository = $realurlUrlDataRepository;
    }
}
