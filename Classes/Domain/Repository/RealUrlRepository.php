<?php
namespace In2code\Realurlconflicts\Domain\Repository;

use In2code\Realurlconflicts\Utility\ArrayUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class RealUrlRepository
 */
class RealUrlRepository
{

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
            ->getQueryBuilderForTable('tx_realurl_pathdata');
    }

    /**
     * Find all duplicates
     *      [
     *       'path' => [123, 133],
     *       'path2' => [22, 334]
     *      ]
     *
     * @return array
     */
    public function findAllDuplicates(): array
    {
        $rows = $this->findAll();
        $paths = [];
        foreach ($rows as $row) {
            $paths[$row['pagepath']][] = $row['page_id'];
        }
        $paths = ArrayUtility::filterArrayOnlyMultipleValues($paths);
        return $paths;
    }

    /**
     * Find all entries
     *
     * @return array
     */
    protected function findAll(): array
    {
        $result = $this->queryBuilder
            ->select('uid', 'page_id', 'pagepath')
            ->from('tx_realurl_pathdata')
            ->where('expire = 0 or (expire > 0 and expire < ' . time() . ')')
            ->setMaxResults(100000)
            ->groupBy('page_id')
            ->execute();
        $rows = $result->fetchAll();
        return $rows;
    }
}
