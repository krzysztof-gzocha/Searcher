<?php

namespace KGzocha\Searcher\Test\Context;

use KGzocha\Searcher\Context\Doctrine\CachedQueryBuilderResultSearchingContext;

/**
 * @author Daniel Ribeiro <drgomesp@gmail.com>
 * @package KGzocha\Searcher\Test\Context
 */
class CachedQueryBuilderResultSearchingContextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\ORM\QueryBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $queryBuilder;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->queryBuilder = $this->getMock('Doctrine\ORM\QueryBuilder', [], [], '', false);
    }

    public function testUseQueryCacheWhenGettingResultsShouldBeCalled()
    {
        $query = $this->getMock('\KGzocha\Searcher\Test\Context\QueryStub', [], ['useResultCache'], '', false);

        $this->queryBuilder
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn($query);

        $query
            ->expects($this->once())
            ->method('useResultCache')
            ->with(true)
            ->willReturn($query);

        $context = new CachedQueryBuilderResultSearchingContext($this->queryBuilder);

        $context->getResults();
    }
}
