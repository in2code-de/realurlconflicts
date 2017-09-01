<?php
declare(strict_types=1);
namespace In2code\Realurlconflicts\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class AbstractRealUrlRepository
 */
abstract class AbstractRealUrlRepository
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
     * @var null|QueryBuilder
     */
    protected $queryBuilder = null;

    /**
     * RealUrlRepository constructor.
     */
    public function __construct()
    {
        $this->queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($this->tableName);
        $this->queryBuilder->getRestrictions()->removeAll();
    }

    /**
     * @param string $path
     * @param int $pid
     * @return bool
     */
    public function deleteByPathAndPid(string $path, int $pid): bool
    {
        $affectedRows = $this->queryBuilder
            ->delete($this->tableName)
            ->where(
                $this->queryBuilder->expr()->eq($this->pathField, $this->queryBuilder->createNamedParameter($path)),
                $this->queryBuilder->expr()->eq('page_id', $pid)
            )
            ->execute();
        return $affectedRows > 0;
    }

    /**
     * @return bool
     */
    public function hasCachingEntriesFromDeletedPages(): bool
    {
        $records = $this->findAllWithDeletedPages();
        return count($records) > 0;
    }

    /**
     * @return bool
     */
    public function deleteWithDeletedPages(): bool
    {
        $uids = $this->findAllWithDeletedPages();
        if (count($uids) > 0) {
            $affectedRows = $this->queryBuilder
                ->delete($this->tableName)
                ->where(
                    $this->queryBuilder->expr()->in('uid', $uids)
                )
                ->execute();
            return $affectedRows > 0;
        }
        return false;
    }

    /**
     * Get array with tx_realurl_pathdata.uid with a relation to a deleted page
     *
     * @return array
     */
    protected function findAllWithDeletedPages(): array
    {
        $result = $this->queryBuilder
            ->select('pd.uid', 'pd.page_id', 'pd.' . $this->pathField)
            ->from($this->tableName, 'pd')
            ->join(
                'pd',
                'pages',
                'p',
                'p.uid = pd.page_id'
            )
            ->where('pd.' . $this->pathField . ' != "" and p.deleted=1')
            ->setMaxResults(10000)
            ->execute();
        $rows = $result->fetchAll();
        $uids = [];
        foreach ($rows as $row) {
            $uids[] = $row['uid'];
        }
        return $uids;
    }
}
