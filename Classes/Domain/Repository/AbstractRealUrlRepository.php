<?php
declare(strict_types=1);
namespace In2code\Realurlconflicts\Domain\Repository;

use In2code\Realurlconflicts\Utility\DatabaseUtility;
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
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function findAllWithDeletedPages(): array
    {
        $connection = DatabaseUtility::getConnectionForTable('pages');
        $uidList = $connection->executeQuery('select group_concat(uid) from pages where deleted=1;')->fetchColumn(0);
        return (array)$this->queryBuilder
            ->select('uid')
            ->from($this->tableName)
            ->where('page_id in (' . $uidList . ')')
            ->setMaxResults(10000)
            ->execute()
            ->fetchAll(0);
    }
}
