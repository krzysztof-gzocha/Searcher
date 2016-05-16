<?php

namespace KGzocha\Searcher\Context\Doctrine;

/**
 * Use this class as a SearchingContext in order to allow all criteria builders
 * to work with Doctrine's QueryBuilder, with result caching enabled by default.
 *
 * @author AbdElKader Bouadjadja <ak.bouadjadja@gmail.com>
 */
class CachedQueryBuilderResultSearchingContext extends QueryBuilderSearchingContext
{
    /**
     * {@inheritdoc}
     */
    public function getResults()
    {
        return parent::getQueryBuilder()
            ->getQuery()
            ->useResultCache(true)
            ->getResult();
    }
}
