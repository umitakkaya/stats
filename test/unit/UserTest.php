<?php

declare(strict_types = 1);

namespace SmAssignment\Test\Unit;

use SmAssignment\User;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \SmAssignment\User
 */
class UserTest extends TestCase
{
    /**
     * Test getters/setters
     *
     * This is stupid test, and you should not take this example as what
     * we're expecting from tests. Consider this more as a placeholder.
     *
     * @test
     * @covers ::setId
     * @covers ::getId
     * @covers ::setName
     * @covers ::getName
     */
    public function testExampleTest(): void
    {
        $user = new User(1, 'John Doe');

        $this->assertEquals(1, $user->getId());
        $this->assertEquals('John Doe', $user->getName());
    }
}
