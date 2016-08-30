<?php

namespace KGzocha\Searcher\Chain;

use KGzocha\Searcher\Criteria\Collection\CriteriaCollectionInterface;
use KGzocha\Searcher\Result\ResultCollection;
use KGzocha\Searcher\SearcherInterface;

/**
 * This class represents whole chain of searchers, but it behaves like regular searcher.
 * It will perform all (not skipped) sub-searches, transform theirs results into CriteriaCollection
 * and pass those criteria to next sub-search.
 * At the end it will return collection of all the results from all the steps.
 *
 * @author Krzysztof Gzocha <krzysztof@propertyfinder.ae>
 */
class ChainSearch implements SearcherInterface
{
    const MINIMUM_CELLS = 2;

    /**
     * @var CellInterface[]
     */
    private $cells;

    /**
     * @param CellInterface[] $cells
     */
    public function __construct(array $cells)
    {
        $this->validateCells($cells);
        $this->cells = $cells;
    }

    /**
     * Will perform multiple sub-searches.
     * Results from first search will be transformed and passed as CriteriaCollection
     * to another sub-search.
     * Whole process will return collection of results from each sub-search.
     *
     * @param CriteriaCollectionInterface $criteriaCollection
     *
     * @return ResultCollection
     */
    public function search(
        CriteriaCollectionInterface $criteriaCollection
    ) {
        $previousCriteria = $criteriaCollection;
        $previousResults = null;
        $result = new ResultCollection();

        foreach ($this->cells as $cell) {
            if ($cell->getTransformer()->skip($previousResults)) {
                continue;
            }

            $previousResults = $cell->getSearcher()->search($previousCriteria);
            $previousCriteria = $this->getNewCriteria(
                $cell,
                $previousCriteria,
                $previousResults
            );

            $this->addResult($result, $cell, $previousResults);
        }

        return $result;
    }

    /**
     * @param CellInterface[] $cells
     *
     * @throws \InvalidArgumentException
     */
    private function validateCells(array $cells)
    {
        if (self::MINIMUM_CELLS > count($cells)) {
            throw new \InvalidArgumentException(
                'At least two searchers are required to create a chain'
            );
        }

        foreach ($cells as $cell) {
            if ($cell instanceof CellInterface) {
                continue;
            }

            throw new \InvalidArgumentException(sprintf(
                'All cells passed to %s should be object and must implement CellInterface',
                get_class($this)
            ));
        }
    }

    /**
     * @param ResultCollection $result
     * @param CellInterface    $cell
     * @param mixed            $previousResults
     *
     * @return ResultCollection
     */
    private function addResult(
        ResultCollection $result,
        CellInterface $cell,
        $previousResults
    ) {
        if ($cell->hasName()) {
            return $result->addNamedItem($cell->getName(), $previousResults);
        }

        return $result->addItem($previousResults);
    }

    /**
     * If $cell has transformer then it will be used to return new criteria.
     * If not old criteria will be returned.
     *
     * @param CellInterface               $cell
     * @param CriteriaCollectionInterface $criteria
     * @param mixed                       $results
     *
     * @return CriteriaCollectionInterface
     */
    private function getNewCriteria(
        CellInterface $cell,
        CriteriaCollectionInterface $criteria,
        $results
    ) {
        if (!$cell->hasTransformer()) {
            return $criteria;
        }

        return $cell
            ->getTransformer()
            ->transform($results, $criteria);
    }
}
