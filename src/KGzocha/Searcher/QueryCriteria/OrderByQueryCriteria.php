<?php

namespace KGzocha\Searcher\QueryCriteria;

/**
 * @author Krzysztof Gzocha <krzysztof@propertyfinder.ae>
 */
class OrderByQueryCriteria implements OrderByQueryCriteriaInterface
{
    /**
     * @var null|string
     */
    private $orderBy;

    /**
     * @param null|string $orderBy
     */
    public function __construct($orderBy = null)
    {
        $this->orderBy = $orderBy;
    }

    /**
     * @return null|string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @param null|string $orderBy
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }

    /**
     * @inheritDoc
     */
    public function shouldBeApplied()
    {
        return $this->orderBy != null;
    }
}
