<?php

namespace KGzocha\Searcher\QueryCriteria\Adapter;

use KGzocha\Searcher\QueryCriteria\OrderByQueryCriteriaInterface;

/**
 * @author Krzysztof Gzocha <krzysztof@propertyfinder.ae>
 */
class MappedOrderByAdapter implements OrderByQueryCriteriaInterface
{
    /**
     * @var OrderByQueryCriteriaInterface
     */
    private $orderBy;

    /**
     * @var array|\ArrayAccess
     */
    private $fieldsMap;

    /**
     * @param OrderByQueryCriteriaInterface $orderBy
     * @param array|\ArrayAccess          $fieldsMap
     */
    public function __construct(
        OrderByQueryCriteriaInterface $orderBy,
        $fieldsMap
    ) {
        $this->checkFieldsMapType($fieldsMap);
        $this->orderBy = $orderBy;
        $this->fieldsMap = $fieldsMap;
    }

    /**
     * @return string|null
     */
    public function getMappedOrderBy()
    {
        if ($this->rawValueExistsInFieldsMap()) {
            return $this->fieldsMap[$this->getOrderBy()];
        }

        return null;
    }

    /**
     * @return array|\ArrayAccess
     */
    public function getFieldsMap()
    {
        return $this->fieldsMap;
    }

    /**
     * @inheritDoc
     */
    public function getOrderBy()
    {
        return $this->orderBy->getOrderBy();
    }

    /**
     * @inheritDoc
     */
    public function setOrderBy($orderBy)
    {
        return $this->orderBy->setOrderBy($orderBy);
    }

    /**
     * @inheritDoc
     */
    public function shouldBeApplied()
    {
        return $this->orderBy->shouldBeApplied() && $this->rawValueExistsInFieldsMap();
    }

    /**
     * @param $fieldsMap
     * @throws \InvalidArgumentException
     */
    private function checkFieldsMapType($fieldsMap)
    {
        if (!is_array($fieldsMap) && !$fieldsMap instanceof \ArrayAccess) {
            throw new \InvalidArgumentException(sprintf(
                'Parameter fieldsMap passed to %s should be an array or \ArrayAccess.'
                . ' Given: %s',
                __CLASS__,
                is_object($fieldsMap) ? get_class($fieldsMap) : gettype($fieldsMap)
            ));
        }
    }

    /**
     * @return bool
     */
    private function rawValueExistsInFieldsMap()
    {
        return array_key_exists($this->getOrderBy(), $this->fieldsMap);
    }
}
