<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CrmSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_core_crm_tables_exist(): void
    {
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasTable('leads'));
        $this->assertTrue(Schema::hasTable('projects'));
        $this->assertTrue(Schema::hasTable('follow_ups'));
        $this->assertTrue(Schema::hasTable('lead_notes'));
        $this->assertTrue(Schema::hasTable('lead_documents'));
    }

    public function test_core_lead_and_project_columns_exist(): void
    {
        $this->assertTrue(Schema::hasColumns('leads', [
            'lead_code',
            'customer_name',
            'company_name',
            'buyer_type',
            'gst_or_website',
            'source',
            'external_source_id',
            'source_payload',
            'status',
            'manager_user_id',
            'assigned_to_user_id',
            'inquiry_at',
            'imported_at',
        ]));

        $this->assertTrue(Schema::hasColumns('projects', [
            'project_code',
            'lead_id',
            'owner_id',
            'project_name',
            'source',
            'po_status',
            'project_status',
            'delivery_date',
        ]));

        $this->assertTrue(Schema::hasColumns('follow_ups', [
            'lead_id',
            'project_id',
            'assigned_to_user_id',
            'subject',
            'type',
            'status',
            'due_at',
        ]));
    }
}
