<?php
declare(strict_types=1);
namespace In2code\Realurlconflicts\Domain\Repository;

/**
 * Class RealurlUrlDataRepository
 */
class RealurlUrlDataRepository extends AbstractRealUrlRepository
{

    /**
     * @var string
     */
    protected $tableName = 'tx_realurl_urldata';

    /**
     * @var string
     */
    protected $pathField = 'speaking_url';
}
