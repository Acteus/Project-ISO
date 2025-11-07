<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_factory_creates_user()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }

    public function test_user_can_be_created_with_role()
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->assertEquals('admin', $user->role);
    }

    public function test_user_can_be_created_with_student_id()
    {
        $user = User::factory()->create([
            'student_id' => 'STU12345',
        ]);

        $this->assertEquals('STU12345', $user->student_id);
    }

    public function test_user_password_is_hashed()
    {
        $user = User::factory()->create([
            'password' => 'plaintext',
        ]);

        $this->assertNotEquals('plaintext', $user->password);
        $this->assertTrue(password_verify('plaintext', $user->password));
    }

    public function test_user_can_have_year_level()
    {
        $user = User::factory()->create([
            'year_level' => 11,
        ]);

        $this->assertEquals(11, $user->year_level);
        $this->assertIsInt($user->year_level);
    }

    public function test_user_can_have_section()
    {
        $user = User::factory()->create([
            'section' => 'A',
        ]);

        $this->assertEquals('A', $user->section);
    }
}


