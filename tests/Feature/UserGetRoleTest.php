<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserGetRoleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function when_is_admin_is_true_and_is_editor_is_false_then_function_get_role_returns_admin()
    {
        $user = User::factory()->create(['is_admin' => true, 'is_editor' => false]);
        $this->assertEquals('admin', $user->getRole());
    }

    /**
     * @test
     */
    public function when_is_admin_is_false_and_is_editor_is_true_then_function_get_role_returns_editor()
    {
        $user = User::factory()->create(['is_admin' => false, 'is_editor' => true]);
        $this->assertEquals('editor', $user->getRole());
    }

    /**
     * @test
     */
    public function when_is_admin_is_true_and_is_editor_is_true_then_function_get_role_returns_admin()
    {
        $user = User::factory()->create(['is_admin' => true, 'is_editor' => true]);
        $this->assertEquals('admin', $user->getRole());
    }

    /**
     * @test
     */
    public function when_is_admin_is_false_and_is_editor_is_false_then_function_get_role_returns_contributor()
    {
        $user = User::factory()->create(['is_admin' => false, 'is_editor' => false]);
        $this->assertEquals('contributor', $user->getRole());
    }
}
