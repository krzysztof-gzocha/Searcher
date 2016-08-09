<?php

namespace KGzocha\Searcher\Test\Criteria\Collection;

use KGzocha\Searcher\Criteria\Collection\CriteriaCollection;
use KGzocha\Searcher\Criteria\CriteriaInterface;

class CriteriaCollectionTest extends \PHPUnit_Framework_TestCase
{
    const NUMBER_OF_QUERY_CRITERIA = 5;

    /**
     * @param mixed $params
     * @expectedException \InvalidArgumentException
     * @dataProvider wrongParamsDataProvider
     */
    public function testConstructorWithWrongParams($params = null)
    {
        new CriteriaCollection($params);
    }

    /**
     * @return array
     */
    public function wrongParamsDataProvider()
    {
        return [
            [],
            [0],
            [1.2],
            [new \stdClass()],
            ['']
        ];
    }

    public function testConstructor()
    {
        $criteria = [];

        for ($i = 1; $i <= self::NUMBER_OF_QUERY_CRITERIA; $i++) {
            $criteria[] = $this->getCriteria();
        }

        $criteriaCollection = new CriteriaCollection($criteria);

        $this->assertCount(self::NUMBER_OF_QUERY_CRITERIA, $criteriaCollection->getCriteria());
    }

    public function testFluentInterface()
    {
        $collection = new CriteriaCollection();
        $this->assertInstanceOf(
            '\KGzocha\Searcher\Criteria\Collection\CriteriaCollection',
            $collection->addCriteria($this->getCriteria())
        );
    }

    public function testCriteriaThatShouldBeApplied()
    {
        $criteria = [];

        for ($i = 1; $i <= self::NUMBER_OF_QUERY_CRITERIA; $i++) {
            $criteria[] = $this->getCriteria();
        }

        $collection = new CriteriaCollection($criteria);
        $collection->addCriteria($this->getCriteriaThatShouldBeApplied());
        $collection->addCriteria($this->getCriteriaThatShouldBeApplied());

        $this->assertCount(2, $collection->getApplicableCriteria());
    }

    /**
     * @return CriteriaInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCriteria()
    {
        return $this
            ->getMockBuilder('\KGzocha\Searcher\Criteria\CriteriaInterface')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return CriteriaInterface
     */
    private function getCriteriaThatShouldBeApplied()
    {
        $criteria = $this->getCriteria();

        $criteria
            ->expects($this->any())
            ->method('shouldBeApplied')
            ->willReturn(true);

        return $criteria;
    }
}

