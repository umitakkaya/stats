<?php

namespace Tests\unit\Statistics\Calculator;

use PHPUnit\Framework\TestCase;
use SocialPost\Dto\SocialPostTo;
use Statistics\Calculator\AveragePostsNumberPerUserPerMonth;
use Statistics\Dto\ParamsTo;
use Statistics\Dto\StatisticsTo;
use Statistics\Enum\StatsEnum;

/**
 * @coversDefaultClass \Statistics\Calculator\AveragePostsNumberPerUserPerMonth
 */
class AveragePostsNumberPerUserPerMonthTest extends TestCase
{
    private const UNITS = 'posts';

    private AveragePostsNumberPerUserPerMonth $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new AveragePostsNumberPerUserPerMonth();

        $this->sut->setParameters(
            (new ParamsTo)->setStatName(StatsEnum::AVERAGE_POSTS_NUMBER_PER_USER_PER_MONTH)
        );
    }


    /**
     * @covers ::doAccumulate
     */
    public function testDoAccumulateNullAuthorId(): void
    {
        $postTo = $this->createMock(SocialPostTo::class);

        $postTo->expects($this->once())
            ->method('getDate')
            ->willReturn(new \DateTime);

        $postTo->expects($this->once())
            ->method('getAuthorId')
            ->willReturn(null);

        $this->sut->accumulateData($postTo);
    }

    /**
     * @covers ::doAccumulate
     */
    public function testDoAccumulateNullDate(): void
    {
        $postTo = $this->createMock(SocialPostTo::class);

        $postTo->expects($this->once())
            ->method('getDate')
            ->willReturn(null);

        $postTo->expects($this->never())
            ->method('getAuthorId');

        $this->sut->accumulateData($postTo);
    }

    /**
     * @covers ::doAccumulate
     */
    public function testDoAccumulate(): void
    {
        $postTo = $this->createMock(SocialPostTo::class);

        $postTo->expects($this->exactly(2))
            ->method('getDate')
            ->willReturn($date = new \DateTime('2022-08-01 00:00:00'));

        $postTo->expects($this->exactly(2))
            ->method('getAuthorId')
            ->willReturn('1');

        $this->sut->accumulateData($postTo);
    }

    /**
     * @covers ::doAccumulate
     * @covers ::doCalculate
     *
     * @dataProvider socialPostToDataProvider
     *
     * @param SocialPostTo[] $posts
     * @param StatisticsTo $expectedCalculationOutcome
     */
    public function testDoCalculate(array $posts, StatisticsTo $expectedCalculationOutcome)
    {
        foreach ($posts as $post) {
            $this->sut->accumulateData($post);
        }

        $this->assertEquals(
            $expectedCalculationOutcome,
            $this->sut->calculate()
        );
    }

    /**
     * @return array<array<SocialPostTo[], StatisticsTo>>
     */
    public function socialPostToDataProvider(): array
    {
        return [
            [
                [
                    $this->createSocialPostMock(2022, 1, '1'),
                    $this->createSocialPostMock(2022, 1, '1'),
                    $this->createSocialPostMock(2022, 1, '2'),
                    $this->createSocialPostMock(2022, 1, '2'),
                    $this->createSocialPostMock(2022, 1, '4'),

                    $this->createSocialPostMock(2022, 2, '3'),

                    $this->createSocialPostMock(2023, 1, '1'),
                    $this->createSocialPostMock(2023, 1, '1'),

                    //following posts should not be included in the statistics
                    //since they are missing either author id or date or both
                    (new SocialPostTo)->setAuthorId('1'),
                    (new SocialPostTo)->setDate($this->createPostDateUsingYearAndMonth(2022, 1)),
                    (new SocialPostTo),
                ],
                (new StatisticsTo())
                    ->setUnits(self::UNITS)
                    ->setName(StatsEnum::AVERAGE_POSTS_NUMBER_PER_USER_PER_MONTH)
                    ->addChild(
                        $this->createExpectedCalculationOutcome(2022, 1, 5, 3),
                    )->addChild(
                        $this->createExpectedCalculationOutcome(2022, 2, 1, 1),
                    )->addChild(
                        $this->createExpectedCalculationOutcome(2023, 1, 2, 1),
                    )
            ]
        ];
    }

    private function createExpectedCalculationOutcome(int $year, int $month, int $numberOfPosts, int $numberOfUsersPosted): StatisticsTo
    {
        $date = $this->createPostDateUsingYearAndMonth($year, $month);

        $period = $date->format('F Y');

        return (new StatisticsTo())
            ->setName(StatsEnum::AVERAGE_POSTS_NUMBER_PER_USER_PER_MONTH)
            ->setSplitPeriod($period)
            ->setValue($numberOfPosts / $numberOfUsersPosted)
            ->setUnits(self::UNITS);
    }

    /**
     * @param int $year
     * @param int $month
     * @param string $authorId
     *
     * @return SocialPostTo
     */
    private function createSocialPostMock(int $year, int $month, string $authorId): SocialPostTo
    {
        $date = new \DateTime();
        $date->setDate($year, $month, 1);
        $date->setTime(0, 0, 0);

        $postTo = $this->createMock(SocialPostTo::class);

        $postTo->expects($this->exactly(2))
            ->method('getDate')
            ->willReturn($date);

        $postTo->expects($this->exactly(2))
            ->method('getAuthorId')
            ->willReturn($authorId);

        return $postTo;
    }

    private function createPostDateUsingYearAndMonth(int $year, int $month): \DateTime
    {
        $date = new \DateTime();
        $date->setDate($year, $month, 1);
        $date->setTime(0, 0, 0);

        return $date;
    }
}
