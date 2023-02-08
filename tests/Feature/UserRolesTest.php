<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


class UserRolesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function when_is_admin_is_true_and_is_editor_is_false_then_method_is_admin_returns_true()
    {
        $user = User::factory()->create(['is_admin' => true, 'is_editor' => false]);
        $this->assertEquals(true, $user->isAdmin());
    }
    /**
     * @test
     */
    public function when_is_admin_is_true_and_is_editor_is_true_then_method_is_admin_returns_true()
    {
        $user = User::factory()->create(['is_admin' => true, 'is_editor' => true]);
        $this->assertEquals(true, $user->isAdmin());
    }
    /**
     * @test
     */
    public function when_is_admin_is_false_and_is_editor_is_false_then_method_is_admin_returns_false()
    {
        $user = User::factory()->create(['is_admin' => false, 'is_editor' => false]);
        $this->assertEquals(false, $user->isAdmin());
    }
    /**
     * @test
     */
    public function when_is_admin_is_false_and_is_editor_is_true_then_method_is_admin_returns_false()
    {
        $user = User::factory()->create(['is_admin' => false, 'is_editor' => true]);
        $this->assertEquals(false, $user->isAdmin());
    }
    /**
     * @test
     */
    public function when_is_admin_is_true_and_is_editor_is_false_then_method_is_editor_returns_false()
    {
        $user = User::factory()->create(['is_admin' => true, 'is_editor' => false]);
        $this->assertEquals(false, $user->isEditor());
    }
    /**
     * @test
     */
    public function when_is_admin_is_true_and_is_editor_is_true_then_method_is_editor_returns_false()
    {
        $user = User::factory()->create(['is_admin' => true, 'is_editor' => true]);
        $this->assertEquals(false, $user->isEditor());
    }
    /**
     * @test
     */
    public function when_is_admin_is_false_and_is_editor_is_false_then_method_is_editor_returns_false()
    {
        $user = User::factory()->create(['is_admin' => false, 'is_editor' => false]);
        $this->assertEquals(false, $user->isEditor());
    }
    /**
     * @test
     */
    public function when_is_admin_is_false_and_is_editor_is_true_then_method_is_editor_returns_true()
    {
        $user = User::factory()->create(['is_admin' => false, 'is_editor' => true]);
        $this->assertEquals(true, $user->isEditor());
    }
    /**
     * @test
     */
    public function when_is_admin_is_true_and_is_editor_is_false_then_method_is_contributor_returns_false()
    {
        $user = User::factory()->create(['is_admin' => true, 'is_editor' => false]);
        $this->assertEquals(false, $user->isContributor());
    }
    /**
     * @test
     */
    public function when_is_admin_is_true_and_is_editor_is_true_then_method_is_contributor_returns_false()
    {
        $user = User::factory()->create(['is_admin' => true, 'is_editor' => true]);
        $this->assertEquals(false, $user->isContributor());
    }
    /**
     * @test
     */
    public function when_is_admin_is_false_and_is_editor_is_false_then_method_is_contributor_returns_true()
    {
        $user = User::factory()->create(['is_admin' => false, 'is_editor' => false]);
        $this->assertEquals(true, $user->isContributor());
    }
    /**
     * @test
     */
    public function when_is_admin_is_false_and_is_editor_is_true_then_method_is_contributor_returns_false()
    {
        $user = User::factory()->create(['is_admin' => false, 'is_editor' => true]);
        $this->assertEquals(false, $user->isContributor());
    }
}
