<?php

declare(strict_types=1);

namespace Tests\Unit\Actions\Reactions;

use App\Actions\Reactions\UpdateUserReputationAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateUserReputationActionTest extends TestCase
{
    use RefreshDatabase;

    private UpdateUserReputationAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new UpdateUserReputationAction;
    }

    /**
     * @test
     *
     * @dataProvider reputationProvider
     */
    public function it_calculates_correct_reputation_impact(string $type, string $action, int $expectedDelta): void
    {
        /** @var User $user */
        $user = User::factory()->create(['reputation_score' => 100]);

        $this->action->execute($user, $type, $action);

        $this->assertEquals(100 + $expectedDelta, $user->reputation_score);
    }

    public static function reputationProvider(): array
    {
        return [
            'add mindblown' => ['mindblown', 'add', 10],
            'add clean' => ['clean', 'add', 10],
            'add security' => ['security', 'add', 10],
            'add optimisable' => ['optimisable', 'add', -2],
            'remove mindblown' => ['mindblown', 'remove', -10],
            'remove optimisable' => ['optimisable', 'remove', 2],
            'switch to mindblown' => ['mindblown', 'switch', 12],
            'switch to optimisable' => ['optimisable', 'switch', -12],
        ];
    }
}
