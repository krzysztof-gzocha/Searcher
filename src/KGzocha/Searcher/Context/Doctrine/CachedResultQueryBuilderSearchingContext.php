<?php

/*
 * This file is part of the searcher package.
 *
 * (c) AbdElKader Bouadjadja <ak.bouadjadja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KGzocha\Searcher\Context\Doctrine;

/**
 * Class CachedResultQueryBuilderSearchingContext
 *
 * @package KGzocha\Searcher\Context\Doctrine
 * @author AbdElKader Bouadjadja <abouadjadja@tradetracker.com>
 */
class CachedResultQueryBuilderSearchingContext extends QueryBuilderSearchingContext
{
    /**
     * @inheritdoc
     */
    public function getResults()
    {
        return $this
            ->getQueryBuilder()
            ->getQuery()
            ->useResultCache(true)
            ->getResult();
    }
}
