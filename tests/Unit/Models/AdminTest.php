<?php

namespace Tests\Unit\Models;

use App\Models\Admin;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_factory_creates_admin()
    {
        $admin = Admin::factory()->create();

        $this->assertDatabaseHas('admins', [
            'id' => $admin->id,
            'email' => $admin->email,
        ]);
    }

    public function test_admin_has_username()
    {
        $admin = Admin::factory()->create([
            'username' => 'testadmin',
        ]);

        $this->assertEquals('testadmin', $admin->username);
    }

    public function test_admin_password_is_hashed()
    {
        $admin = Admin::factory()->create([
            'password' => 'plaintext',
        ]);

        $this->assertNotEquals('plaintext', $admin->password);
        $this->assertTrue(password_verify('plaintext', $admin->password));
    }

    public function test_admin_can_create_api_token()
    {
        $admin = Admin::factory()->create();

        $token = $admin->createToken('test-token')->plainTextToken;

        $this->assertNotNull($token);
        $this->assertIsString($token);
    }

    public function test_admin_has_audit_logs_relationship()
    {
        $admin = Admin::factory()->create();
        $auditLog = AuditLog::factory()->create([
            'user_id' => $admin->id,
        ]);

        $this->assertTrue($admin->auditLogs->contains($auditLog));
    }

    public function test_admin_can_be_unverified()
    {
        $admin = Admin::factory()->unverified()->create();

        $this->assertNull($admin->email_verified_at);
    }
}

