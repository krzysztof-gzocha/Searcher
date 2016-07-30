<?php

namespace KGzocha\Searcher\Test\Context\Doctrine;

use KGzocha\Searcher\Context\Doctrine\ODMBuilderSearchingContext;

/**
 * @author Krzysztof Gzocha <krzysztof@propertyfinder.ae>
 */
class ODMBuilderSearchingContextTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $context = new ODMBuilderSearchingContext($builder = $this->getBuilderMock());

        $this->assertEquals($builder, $context->getQueryBuilder());
    }

    public function testGetResults()
    {
        $context = new ODMBuilderSearchingContext($this->getBuilderMock(
            $results = [1, 2, 3]
        ));

        $this->assertEquals($results, $context->getResults());
    }

    /**
     * @param array $results
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getBuilderMock(array $results = [])
    {
        $mock = $this
            ->getMockBuilder('\Doctrine\ODM\MongoDB\Query\Builder')
            ->disableOriginalConstructor()
            ->getMock();

        $query = $this
            ->getMockBuilder('\Doctrine\ODM\MongoDB\Query\Query')
            ->disableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getQuery')
            ->willReturn($query);

        $query
            ->expects($this->any())
            ->method('execute')
            ->willReturn($results);

        return $mock;
    }
}
