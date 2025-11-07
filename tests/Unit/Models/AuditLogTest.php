<?php

namespace Tests\Unit\Models;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_audit_log_factory_creates_log()
    {
        $log = AuditLog::factory()->create();

        $this->assertDatabaseHas('audit_logs', [
            'id' => $log->id,
            'action' => $log->action,
        ]);
    }

    public function test_audit_log_can_be_anonymous()
    {
        $log = AuditLog::factory()->anonymous()->create();

        $this->assertNull($log->user_id);
        $this->assertStringContainsString('anonymous', $log->description);
    }

    public function test_audit_log_has_user_relationship()
    {
        $user = User::factory()->create();
        $log = AuditLog::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals($user->id, $log->user->id);
    }

    public function test_audit_log_can_store_old_and_new_values()
    {
        $oldValues = ['field' => 'old_value'];
        $newValues = ['field' => 'new_value'];

        $log = AuditLog::factory()->withChanges($oldValues, $newValues)->create();

        $this->assertEquals($oldValues, $log->old_values);
        $this->assertEquals($newValues, $log->new_values);
    }

    public function test_audit_log_casts_json_fields()
    {
        $log = AuditLog::factory()->create([
            'old_values' => ['key' => 'value'],
            'new_values' => ['key' => 'new_value'],
        ]);

        $this->assertIsArray($log->old_values);
        $this->assertIsArray($log->new_values);
    }

    public function test_audit_log_stores_ip_address()
    {
        $ip = '192.168.1.1';
        $log = AuditLog::factory()->create([
            'ip_address' => $ip,
        ]);

        $this->assertEquals($ip, $log->ip_address);
    }
}


