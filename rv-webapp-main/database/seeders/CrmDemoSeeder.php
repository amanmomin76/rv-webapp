<?php

namespace Database\Seeders;

use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\LeadDocument;
use App\Models\LeadNote;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CrmDemoSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        FollowUp::query()->truncate();
        LeadDocument::query()->truncate();
        LeadNote::query()->truncate();
        Project::query()->truncate();
        Lead::query()->truncate();
        User::query()->truncate();

        Schema::enableForeignKeyConstraints();

        $users = collect([
                [
                    'name' => 'Owner Admin',
                    'email' => 'owner@leadflowcrm.com',
                    'phone' => '9000000001',
                    'role' => 'Admin/Owner',
                    'employment_status' => 'Active',
                    'joined_at' => '2025-01-02',
                    'performance_percent' => 96,
                ],
                [
                    'name' => 'Rahul Sharma',
                    'email' => 'rahul.sharma@leadflowcrm.com',
                    'phone' => '9876543210',
                    'role' => 'Manager',
                    'employment_status' => 'Active',
                    'joined_at' => '2025-02-14',
                    'performance_percent' => 92,
                ],
                [
                    'name' => 'Anil Kumar',
                    'email' => 'anil.kumar@leadflowcrm.com',
                    'phone' => '9734567890',
                    'role' => 'Manager',
                    'employment_status' => 'Active',
                    'joined_at' => '2025-03-18',
                    'performance_percent' => 88,
                ],
                [
                    'name' => 'Neha Verma',
                    'email' => 'neha.verma@leadflowcrm.com',
                    'phone' => '9987766555',
                    'role' => 'Agent',
                    'employment_status' => 'Active',
                    'joined_at' => '2025-04-05',
                    'performance_percent' => 86,
                ],
                [
                    'name' => 'Sanjay Patel',
                    'email' => 'sanjay.patel@leadflowcrm.com',
                    'phone' => '9345678901',
                    'role' => 'Agent',
                    'employment_status' => 'Active',
                    'joined_at' => '2025-05-01',
                    'performance_percent' => 81,
                ],
                [
                    'name' => 'Vikram Singh',
                    'email' => 'vikram.singh@leadflowcrm.com',
                    'phone' => '9010101010',
                    'role' => 'Agent',
                    'employment_status' => 'Active',
                    'joined_at' => '2025-05-20',
                    'performance_percent' => 79,
                ],
                [
                    'name' => 'Pooja Shah',
                    'email' => 'pooja.shah@leadflowcrm.com',
                    'phone' => '7878787878',
                    'role' => 'Agent',
                    'employment_status' => 'Active',
                    'joined_at' => '2025-06-10',
                    'performance_percent' => 84,
                ],
                [
                    'name' => 'Arjun Desai',
                    'email' => 'arjun.desai@leadflowcrm.com',
                    'phone' => '9090909090',
                    'role' => 'Agent',
                    'employment_status' => 'Inactive',
                    'joined_at' => '2025-07-08',
                    'performance_percent' => 71,
                ],
        ])->mapWithKeys(function (array $user): array {
            $model = User::query()->create([
                ...$user,
                'email_verified_at' => now(),
                'password' => '12345',
            ]);

            return [$user['email'] => $model];
        });

        $leads = collect([
                $this->lead('LF2601', 'Rahul Patel', 'Patel Industries', 'rahul@patelindustries.in', 'Ahmedabad', 'Gujarat', 'India', 'Manufacturer', '24AAACP1111A1Z8 / patelindustries.in', 'IndiaMART', 'New', 'rahul.sharma@leadflowcrm.com', '2026-01-12 10:15:00', 'Need a multi-head weighing machine with PLC support.', 'Multi-head Weigher', '2 Units', 'Rs 12,00,000', '45 Days', 'Packaging Machinery'),
                $this->lead('LF2602', 'Amit Singh', 'Singh Trading', 'amit@singhtrading.in', 'Kanpur', 'Uttar Pradesh', 'India', 'Trader', '09AABCS2222C1Z6 / singhtrading.in', 'Website', 'Contacted', 'anil.kumar@leadflowcrm.com', '2026-01-26 14:20:00', 'Looking for a cost-effective powder filling setup with AMC support.', 'Powder Filling Machine', '1 Line', 'Rs 8,50,000', '30 Days', 'Filling Systems'),
                $this->lead('LF2603', 'Neha Gupta', 'Gupta Enterprises', 'neha@guptaenterprises.in', 'Indore', 'Madhya Pradesh', 'India', 'Manufacturer', '23AACCG3333M1Z2 / guptaenterprises.in', 'IndiaMART', 'Interested', 'neha.verma@leadflowcrm.com', '2026-02-08 11:10:00', 'Wants a semi-automatic sealing line for edible products.', 'Induction Sealer', '3 Units', 'Rs 6,25,000', '28 Days', 'Sealing Solutions'),
                $this->lead('LF2604', 'Rajesh Kumar', 'Kumar Electrical', 'rajesh@kumarelectrical.in', 'Jaipur', 'Rajasthan', 'India', 'OEM', '08AABCK4444J1Z3 / kumarelectrical.in', 'Alibaba', 'Negotiation', 'sanjay.patel@leadflowcrm.com', '2026-02-21 16:05:00', 'Final commercial discussion pending for a carton taping line.', 'Carton Taping Machine', '1 Unit', 'Rs 4,90,000', '21 Days', 'End-of-Line Automation'),
                $this->lead('LF2605', 'Vikram Mehta', 'Mehta Corp', 'vikram@mehtacorp.in', 'Mumbai', 'Maharashtra', 'India', 'Brand Owner', '27AACCM5555A1Z4 / mehtacorp.in', 'Website', 'Negotiation', 'vikram.singh@leadflowcrm.com', '2026-03-04 13:45:00', 'Buyer evaluating automation ROI and wants production videos.', 'Pouch Packing Machine', '1 Unit', 'Rs 14,75,000', '60 Days', 'Flexible Packaging'),
                $this->lead('LF2606', 'Pooja Shah', 'Shah & Sons', 'pooja@shahsons.in', 'Vadodara', 'Gujarat', 'India', 'Distributor', '24AABCS6666E1Z7 / shahsons.in', 'IndiaMART', 'PO Received', 'rahul.sharma@leadflowcrm.com', '2026-03-18 09:30:00', 'PO received for a compact shrink tunnel and conveyor set.', 'Shrink Tunnel', '1 Set', 'Rs 5,20,000', '24 Days', 'Shrink Packaging'),
                $this->lead('LF2607', 'Sanjeev Yadav', 'Yadav Fabrication', 'sanjeev@yadavfab.in', 'Faridabad', 'Haryana', 'India', 'Fabricator', '06AACCY7777R1Z1 / yadavfab.in', 'WhatsApp', 'PO Received', 'anil.kumar@leadflowcrm.com', '2026-04-09 15:00:00', 'Advance cleared for a custom band sealer with heavy-duty rollers.', 'Band Sealer', '2 Units', 'Rs 3,40,000', '18 Days', 'Sealing Solutions'),
                $this->lead('LF2608', 'Mohit Jain', 'Jain Automation', 'mohit@jainautomation.in', 'Pune', 'Maharashtra', 'India', 'System Integrator', '27AACCM8181A1Z1 / jainautomation.in', 'Website', 'Lost', 'neha.verma@leadflowcrm.com', '2026-04-24 12:10:00', 'Customer paused automation purchase after internal budget freeze.', 'Checkweigher', '1 Unit', 'Rs 7,10,000', '35 Days', 'Inspection Systems'),
                $this->lead('LF2609', 'Ritesh Saini', 'Saini Packtech', 'ritesh@sainipacktech.in', 'Ludhiana', 'Punjab', 'India', 'Manufacturer', '03AACCS9292L1Z1 / sainipacktech.in', 'IndiaMART', 'New', 'rahul.sharma@leadflowcrm.com', '2026-05-05 10:00:00', 'Needs a quick proposal for a vertical FFS machine with servo dosing.', 'Vertical FFS Machine', '1 Line', 'Rs 16,50,000', '50 Days', 'Flexible Packaging'),
                $this->lead('LF2610', 'Karan Malhotra', 'Malhotra Labels', 'karan@malhotralabels.in', 'Delhi', 'Delhi', 'India', 'Manufacturer', '07AACCM8888P1Z9 / malhotralabels.in', 'Alibaba', 'Contacted', 'sanjay.patel@leadflowcrm.com', '2026-05-12 12:20:00', 'Requested sample videos and sticker applicator line compatibility.', 'Label Applicator', '2 Units', 'Rs 9,40,000', '32 Days', 'Labeling Systems'),
                $this->lead('LF2611', 'Divya Nair', 'Nair Packaging', 'divya@nairpackaging.in', 'Kochi', 'Kerala', 'India', 'Food Processor', '32AACCN9999G1Z4 / nairpackaging.in', 'Website', 'Interested', 'pooja.shah@leadflowcrm.com', '2026-05-20 17:15:00', 'Team wants a final hybrid quotation with service visit pricing.', 'Tray Sealer', '1 Unit', 'Rs 11,20,000', '42 Days', 'Food Packaging'),
                $this->lead('LF2612', 'Arjun Desai', 'Desai Conveyor Works', 'arjun@desaiconveyor.in', 'Surat', 'Gujarat', 'India', 'Manufacturer', '24AACCD1010D1Z6 / desaiconveyor.in', 'WhatsApp', 'Negotiation', 'vikram.singh@leadflowcrm.com', '2026-06-02 11:35:00', 'Buyer comparing conveyor integration cost with local vendor quote.', 'Conveyor Line', '1 System', 'Rs 13,80,000', '40 Days', 'Conveying Systems'),
        ])->mapWithKeys(function (array $lead) use ($users): array {
            $managerEmail = $this->managerEmailForLead($lead);
            $inquiryAt = Carbon::parse($lead['inquiry_at']);

            $model = Lead::query()->create([
                'lead_code' => $lead['lead_code'],
                'customer_name' => $lead['customer_name'],
                'customer_mobile' => $lead['customer_mobile'],
                'customer_email' => $lead['customer_email'],
                'company_name' => $lead['company_name'],
                'city' => $lead['city'],
                'state' => $lead['state'],
                'country' => $lead['country'],
                'buyer_type' => $lead['buyer_type'],
                'gst_or_website' => $lead['gst_or_website'],
                'requirement_message' => $lead['requirement_message'],
                'requirement_product' => $lead['requirement_product'],
                'requirement_quantity' => $lead['requirement_quantity'],
                'requirement_budget' => $lead['requirement_budget'],
                'requirement_delivery' => $lead['requirement_delivery'],
                'requirement_category' => $lead['requirement_category'],
                'source' => $lead['source'],
                'external_source_id' => $this->externalSourceId($lead),
                'source_payload' => [
                    'platform' => $lead['source'],
                    'import_mode' => $this->importMode($lead['source']),
                    'product' => $lead['requirement_product'],
                    'routing_rule' => $this->routingRuleForLead($lead),
                ],
                'status' => $lead['status'],
                'manager_user_id' => $users[$managerEmail]->id,
                'assigned_to_user_id' => $users[$lead['assigned_to_email']]->id,
                'inquiry_at' => $inquiryAt,
                'imported_at' => $inquiryAt->copy()->addMinutes(4),
                'created_at' => $inquiryAt,
                'updated_at' => $inquiryAt,
            ]);

            return [$lead['lead_code'] => $model];
        });

        $projects = collect([
                $this->project('PR2601', 'Patel Industries Servo Line', 'LF2601', 'rahul.sharma@leadflowcrm.com', 'IndiaMART', 'PO Awaited', 'Negotiation', 1200000, '2026-06-28', 40),
                $this->project('PR2602', 'Singh Powder Fill Setup', 'LF2602', 'anil.kumar@leadflowcrm.com', 'Website', 'PO Awaited', 'Interested', 850000, '2026-06-15', 35),
                $this->project('PR2603', 'Gupta Sealing Expansion', 'LF2603', 'neha.verma@leadflowcrm.com', 'IndiaMART', 'PO Awaited', 'In Production', 625000, '2026-06-22', 62),
                $this->project('PR2604', 'Kumar Carton Finish', 'LF2604', 'sanjay.patel@leadflowcrm.com', 'Alibaba', 'PO Received', 'Dispatch Ready', 490000, '2026-06-11', 84),
                $this->project('PR2605', 'Mehta Flexible Pack Cell', 'LF2605', 'vikram.singh@leadflowcrm.com', 'Website', 'PO Awaited', 'Negotiation', 1475000, '2026-07-12', 28),
                $this->project('PR2606', 'Shah Shrink Pack Order', 'LF2606', 'rahul.sharma@leadflowcrm.com', 'IndiaMART', 'PO Received', 'In Production', 520000, '2026-06-18', 72),
                $this->project('PR2501', 'Yadav Band Sealer Dispatch', 'LF2607', 'anil.kumar@leadflowcrm.com', 'WhatsApp', 'PO Received', 'Completed', 340000, '2026-05-26', 100),
                $this->project('PR2502', 'Malhotra Label Line', 'LF2610', 'sanjay.patel@leadflowcrm.com', 'Alibaba', 'PO Received', 'Completed', 940000, '2026-05-24', 100),
                $this->project('PR2503', 'Nair Tray Sealer Closeout', 'LF2611', 'pooja.shah@leadflowcrm.com', 'Website', 'PO Received', 'Completed', 1120000, '2026-05-31', 100),
        ])->mapWithKeys(function (array $project) use ($users, $leads): array {
            $lead = $leads[$project['lead_code']];

            $model = Project::query()->create([
                'project_code' => $project['project_code'],
                'lead_id' => $lead->id,
                'owner_id' => $users[$project['owner_email']]->id,
                'project_name' => $project['project_name'],
                'customer_name' => $lead->company_name ?? $lead->customer_name,
                'source' => $project['source'],
                'po_status' => $project['po_status'],
                'project_status' => $project['project_status'],
                'value_amount' => $project['value_amount'],
                'currency_code' => 'INR',
                'delivery_date' => $project['delivery_date'],
                'progress_percent' => $project['progress_percent'],
                'created_at' => Carbon::parse($lead->inquiry_at)->addDays(3),
                'updated_at' => Carbon::parse($lead->inquiry_at)->addDays(6),
            ]);

            return [$project['project_code'] => $model];
        });

        $followUps = [
                $this->followUp('LF2601', 'PR2601', 'rahul.sharma@leadflowcrm.com', '2026-06-06 11:00:00', 'Call', 'Follow up', 'Confirm approval timeline and engineering sign-off.', 'Due Soon', 'project'),
                $this->followUp('LF2602', 'PR2602', 'anil.kumar@leadflowcrm.com', '2026-06-06 14:30:00', 'Email', 'Follow up', 'Share AMC break-up and line layout option B.', 'Pending', 'project'),
                $this->followUp('LF2603', 'PR2603', 'neha.verma@leadflowcrm.com', '2026-06-07 09:45:00', 'WhatsApp', 'Follow up', 'Send updated semi-auto sealing video and footprint.', 'Pending', 'project'),
                $this->followUp('LF2604', 'PR2604', 'sanjay.patel@leadflowcrm.com', '2026-06-06 16:30:00', 'Call', 'Dispatch confirmation', 'Final negotiation call before dispatch confirmation.', 'Due Soon', 'project'),
                $this->followUp('LF2605', 'PR2605', 'vikram.singh@leadflowcrm.com', '2026-06-08 12:15:00', 'Meeting', 'ROI review', 'Review ROI concerns with plant operations team.', 'Pending', 'lead'),
                $this->followUp('LF2606', 'PR2606', 'rahul.sharma@leadflowcrm.com', '2026-06-06 17:15:00', 'Email', 'Production update', 'Send production progress photos and packing ETA.', 'Due Soon', 'project'),
                $this->followUp('LF2610', 'PR2502', 'sanjay.patel@leadflowcrm.com', '2026-05-18 10:00:00', 'Call', 'Reference request', 'Close completed project review and ask for a reference note.', 'Completed', 'project'),
        ];

        foreach ($followUps as $followUp) {
            FollowUp::query()->create([
                'lead_id' => $leads[$followUp['lead_code']]->id,
                'project_id' => $followUp['project_code'] ? $projects[$followUp['project_code']]->id : null,
                'assigned_to_user_id' => $users[$followUp['owner_email']]->id,
                'subject' => $followUp['subject'],
                'notes' => $followUp['notes'],
                'type' => $followUp['type'],
                'status' => $followUp['status'],
                'source_context' => $followUp['source_context'],
                'due_at' => Carbon::parse($followUp['due_at']),
                'completed_at' => $followUp['status'] === 'Completed' ? Carbon::parse($followUp['due_at'])->addHour() : null,
                'created_at' => Carbon::parse($followUp['due_at'])->subDay(),
                'updated_at' => Carbon::parse($followUp['due_at'])->subHours(6),
            ]);
        }

        foreach ([
                ['lead_code' => 'LF2601', 'author_email' => 'owner@leadflowcrm.com', 'note_text' => 'Discussed line speed targets and confirmed that servo upgrades are acceptable if ROI stays under 14 months.', 'created_at' => '2026-06-05 09:30:00'],
                ['lead_code' => 'LF2601', 'author_email' => 'rahul.sharma@leadflowcrm.com', 'note_text' => 'Customer wants the full quotation pack emailed after commercial review with final floor layout attached.', 'created_at' => '2026-06-05 17:10:00'],
                ['lead_code' => 'LF2605', 'author_email' => 'vikram.singh@leadflowcrm.com', 'note_text' => 'Buyer asked for production videos and utility consumption figures before finance sign-off.', 'created_at' => '2026-06-04 13:20:00'],
                ['lead_code' => 'LF2611', 'author_email' => 'pooja.shah@leadflowcrm.com', 'note_text' => 'Hybrid quotation shared. Waiting for service visit sign-off from plant QA.', 'created_at' => '2026-06-03 11:05:00'],
        ] as $note) {
            LeadNote::query()->create([
                'lead_id' => $leads[$note['lead_code']]->id,
                'author_user_id' => $users[$note['author_email']]->id,
                'note_text' => $note['note_text'],
                'created_at' => Carbon::parse($note['created_at']),
                'updated_at' => Carbon::parse($note['created_at']),
            ]);
        }

        foreach ([
                ['lead_code' => 'LF2601', 'uploaded_by' => 'owner@leadflowcrm.com', 'file_name' => 'patel-industries-requirement-sheet.pdf', 'file_type' => 'PDF', 'path' => 'demo/leads/LF2601/patel-industries-requirement-sheet.pdf', 'uploaded_at' => '2026-06-05 18:00:00'],
                ['lead_code' => 'LF2601', 'uploaded_by' => 'rahul.sharma@leadflowcrm.com', 'file_name' => 'patel-industries-quotation-draft.xlsx', 'file_type' => 'XLS', 'path' => 'demo/leads/LF2601/patel-industries-quotation-draft.xlsx', 'uploaded_at' => '2026-06-05 18:10:00'],
                ['lead_code' => 'LF2605', 'uploaded_by' => 'vikram.singh@leadflowcrm.com', 'file_name' => 'mehta-corp-layout-plan.dwg', 'file_type' => 'DWG', 'path' => 'demo/leads/LF2605/mehta-corp-layout-plan.dwg', 'uploaded_at' => '2026-06-04 14:00:00'],
                ['lead_code' => 'LF2611', 'uploaded_by' => 'pooja.shah@leadflowcrm.com', 'file_name' => 'nair-packaging-commercial-pack.pdf', 'file_type' => 'PDF', 'path' => 'demo/leads/LF2611/nair-packaging-commercial-pack.pdf', 'uploaded_at' => '2026-06-03 12:30:00'],
        ] as $document) {
            LeadDocument::query()->create([
                'lead_id' => $leads[$document['lead_code']]->id,
                'uploaded_by_user_id' => $users[$document['uploaded_by']]->id,
                'file_name' => $document['file_name'],
                'file_type' => $document['file_type'],
                'disk' => 'public',
                'path' => $document['path'],
                'uploaded_at' => Carbon::parse($document['uploaded_at']),
                'created_at' => Carbon::parse($document['uploaded_at']),
                'updated_at' => Carbon::parse($document['uploaded_at']),
            ]);
        }
    }

    private function lead(
        string $leadCode,
        string $customerName,
        string $companyName,
        string $customerEmail,
        string $city,
        string $state,
        string $country,
        string $buyerType,
        string $gstOrWebsite,
        string $source,
        string $status,
        string $assignedToEmail,
        string $inquiryAt,
        string $requirementMessage,
        string $requirementProduct,
        string $requirementQuantity,
        string $requirementBudget,
        string $requirementDelivery,
        string $requirementCategory,
    ): array {
        return [
            'lead_code' => $leadCode,
            'customer_name' => $customerName,
            'customer_mobile' => $this->phoneFromCode($leadCode),
            'customer_email' => $customerEmail,
            'company_name' => $companyName,
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'buyer_type' => $buyerType,
            'gst_or_website' => $gstOrWebsite,
            'source' => $source,
            'status' => $status,
            'assigned_to_email' => $assignedToEmail,
            'inquiry_at' => $inquiryAt,
            'requirement_message' => $requirementMessage,
            'requirement_product' => $requirementProduct,
            'requirement_quantity' => $requirementQuantity,
            'requirement_budget' => $requirementBudget,
            'requirement_delivery' => $requirementDelivery,
            'requirement_category' => $requirementCategory,
        ];
    }

    private function project(
        string $projectCode,
        string $projectName,
        string $leadCode,
        string $ownerEmail,
        string $source,
        string $poStatus,
        string $projectStatus,
        float $valueAmount,
        string $deliveryDate,
        int $progressPercent,
    ): array {
        return [
            'project_code' => $projectCode,
            'project_name' => $projectName,
            'lead_code' => $leadCode,
            'owner_email' => $ownerEmail,
            'source' => $source,
            'po_status' => $poStatus,
            'project_status' => $projectStatus,
            'value_amount' => $valueAmount,
            'delivery_date' => $deliveryDate,
            'progress_percent' => $progressPercent,
        ];
    }

    private function followUp(
        string $leadCode,
        ?string $projectCode,
        string $ownerEmail,
        string $dueAt,
        string $type,
        string $subject,
        string $notes,
        string $status,
        string $sourceContext,
    ): array {
        return [
            'lead_code' => $leadCode,
            'project_code' => $projectCode,
            'owner_email' => $ownerEmail,
            'due_at' => $dueAt,
            'type' => $type,
            'subject' => $subject,
            'notes' => $notes,
            'status' => $status,
            'source_context' => $sourceContext,
        ];
    }

    private function managerEmailForLead(array $lead): string
    {
        if ($lead['source'] === 'IndiaMART' && $lead['state'] === 'Gujarat') {
            return 'rahul.sharma@leadflowcrm.com';
        }

        if ($lead['source'] === 'Alibaba') {
            return 'anil.kumar@leadflowcrm.com';
        }

        if (str_contains(strtolower($lead['requirement_category']), 'packaging')) {
            return 'rahul.sharma@leadflowcrm.com';
        }

        return in_array($lead['assigned_to_email'], [
            'rahul.sharma@leadflowcrm.com',
            'anil.kumar@leadflowcrm.com',
        ], true)
            ? $lead['assigned_to_email']
            : 'anil.kumar@leadflowcrm.com';
    }

    private function externalSourceId(array $lead): ?string
    {
        return match ($lead['source']) {
            'IndiaMART' => 'IM-'.$lead['lead_code'],
            'Alibaba' => 'ALB-'.$lead['lead_code'],
            'Website' => 'WEB-'.$lead['lead_code'],
            'WhatsApp' => 'WA-'.$lead['lead_code'],
            default => null,
        };
    }

    private function importMode(string $source): string
    {
        return match ($source) {
            'IndiaMART' => 'api_push_pull',
            'Alibaba' => 'seller_export',
            'TradeIndia', 'Aajjo' => 'csv_or_api',
            default => 'manual',
        };
    }

    private function routingRuleForLead(array $lead): string
    {
        if ($lead['source'] === 'IndiaMART' && $lead['state'] === 'Gujarat') {
            return 'IndiaMART + Gujarat';
        }

        if ($lead['source'] === 'Alibaba') {
            return 'Alibaba export lead';
        }

        if (str_contains(strtolower($lead['requirement_category']), 'packaging')) {
            return 'Packaging product owner';
        }

        return 'Manual review';
    }

    private function phoneFromCode(string $leadCode): string
    {
        return match ($leadCode) {
            'LF2601' => '9876543210',
            'LF2602' => '9734567890',
            'LF2603' => '9987766555',
            'LF2604' => '9345678901',
            'LF2605' => '9010101010',
            'LF2606' => '7878787878',
            'LF2607' => '9090909090',
            'LF2608' => '6565656565',
            'LF2609' => '9898989898',
            'LF2610' => '9123456789',
            'LF2611' => '9345612780',
            default => '9555512345',
        };
    }
}
