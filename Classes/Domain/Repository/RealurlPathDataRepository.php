<?php
declare(strict_types=1);
namespace In2code\Realurlconflicts\Domain\Repository;

use In2code\Realurlconflicts\Utility\ArrayUtility;
use In2code\Realurlconflicts\Utility\BackendUtility;
use In2code\Realurlconflicts\Utility\ObjectUtility;
use TYPO3\CMS\Core\Database\QueryGenerator;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class RealUrlPathDataRepository
 */
class RealurlPathDataRepository extends AbstractRealUrlRepository
{

    /**
     * @var string
     */
    protected $tableName = 'tx_realurl_pathdata';

    /**
     * @var string
     */
    protected $pathField = 'pagepath';

    /**
     * Find all duplicates
     *      [
     *         'path' => [
     *            ['uid' => 123],
     *            ['uid' => 133]
     *         ],
     *         'path2' => [
     *            ['uid' => 22],
     *            ['uid' => 334]
     *        ]
     *     ]
     *
     * @param int $startPid
     * @return array
     */
    public function findAllDuplicates(int $startPid): array
    {
        $rows = $this->findAllFromStartPid($startPid);
        $paths = [];
        foreach ($rows as $row) {
            $paths[$row['pagepath']][] = $row['page_id'];
        }
        $paths = ArrayUtility::filterArrayOnlyMultipleValues($paths);
        $paths = $this->enrichPageIdentifiersWithRecord($paths);
        return $paths;
    }

    /**
     * @param int $startPid
     * @return array
     */
    protected function findAllFromStartPid(int $startPid): array
    {
        $result = $this->queryBuilder
            ->select('uid', 'page_id', 'pagepath')
            ->from($this->tableName)
            ->where($this->getWhereStringForStartPid($startPid))
            ->setMaxResults(100000)
            ->groupBy('page_id', 'pagepath')
            ->execute();
        $rows = $result->fetchAll();
        return $rows;
    }

    /**
     * @param array $paths
     * @return array
     */
    protected function enrichPageIdentifiersWithRecord(array $paths): array
    {
        foreach ($paths as $path => $uids) {
            foreach ($uids as $key => $uid) {
                $paths[$path][$key] = BackendUtility::getPagePropertiesFromUid($uid);
            }
        }
        return $paths;
    }

    /**
     * @param int $startPid
     * @return string
     */
    protected function getWhereStringForStartPid(int $startPid): string
    {
        $string = '(expire = 0 or (expire > 0 and expire < ' . time() . '))';
        if ($startPid > 0) {
            $string .= ' and page_id in (' . implode(',', $this->getPagesFromStartPoint($startPid)) . ')';
        }
        return $string;
    }

    /**
     * @param int $startPid
     * @return array
     */
    protected function getPagesFromStartPoint(int $startPid): array
    {
        $queryGenerator = ObjectUtility::getObjectManager()->get(QueryGenerator::class);
        $list = $queryGenerator->getTreeList($startPid, 20, 0, 1);
        return GeneralUtility::trimExplode(',', $list, true);
    }
}
