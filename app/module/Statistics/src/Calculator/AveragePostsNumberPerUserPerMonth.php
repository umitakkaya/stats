<?php

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

class AveragePostsNumberPerUserPerMonth extends AbstractCalculator
{
    protected const UNITS = 'posts';

    private array $averageNumberOfPostsPerUserPerMonth = [];

    protected function doAccumulate(SocialPostTo $postTo): void
    {
        if (null === $postTo->getDate() || null === $postTo->getAuthorId())  {
            return;
        }

        $period = $postTo->getDate()->format('F Y');
        $userId = $postTo->getAuthorId();

        $this->averageNumberOfPostsPerUserPerMonth[$period][$userId] ??= 0;
        $this->averageNumberOfPostsPerUserPerMonth[$period][$userId]++;
    }

    protected function doCalculate(): StatisticsTo
    {
        $statisticsTo = new StatisticsTo();

        foreach ($this->averageNumberOfPostsPerUserPerMonth as $month => $userPosts) {

            $numberOfPostsPerUserInGivenMonth        = array_sum($userPosts);
            $averageNumberOfPostsPerUserInGivenMonth = $numberOfPostsPerUserInGivenMonth / count($userPosts);


            $child = (new StatisticsTo())
                ->setName($this->parameters->getStatName())
                ->setSplitPeriod($month)
                ->setValue($averageNumberOfPostsPerUserInGivenMonth)
                ->setUnits(self::UNITS);

            $statisticsTo->addChild($child);
        }

        return $statisticsTo;
    }
}
