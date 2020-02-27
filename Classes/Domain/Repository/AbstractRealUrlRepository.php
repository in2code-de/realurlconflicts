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
     * RealUrlRepository constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string $path
     * @param int $pid
     * @return bool
     */
    public function deleteByPathAndPid(string $path, int $pid): bool
    {
        $queryBuilder = $this->getQueryBuilder();
        $affectedRows = $queryBuilder
            ->delete($this->tableName)
            ->where(
                $queryBuilder->expr()->eq($this->pathField, $queryBuilder->createNamedParameter($path)),
                $queryBuilder->expr()->eq('page_id', $pid)
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
        $queryBuilder = $this->getQueryBuilder();
        $uids = $this->findAllWithDeletedPages();

        $uidList =  implode(',',array_map(function($a) {return implode('~',$a);},$uids));

        if (count($uids) > 0) {
            $affectedRows = $this->getQueryBuilder()
                ->delete($this->tableName)
                ->where(
                    $queryBuilder->expr()->in('uid', $uidList)
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
        $queryBuilder = $this->getQueryBuilder();
        $connection = DatabaseUtility::getConnectionForTable('pages');
        $uids = $connection->executeQuery('select uid from pages where deleted=1;')->fetchAll();

        $uidList =  implode(',',array_map(function($a) {return implode('~',$a);},$uids));

        return (array)$queryBuilder
            ->select('uid')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->in('page_id', $uidList)
            )
            ->execute()
            ->fetchAll(0);
    }

    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($this->tableName);
        $queryBuilder->getRestrictions()->removeAll();

        return $queryBuilder;
    }
}
