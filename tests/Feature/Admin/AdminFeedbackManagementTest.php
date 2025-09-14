<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use App\Models\Feedback;
use App\Models\WasteReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFeedbackManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_feedback_list(): void
    {
        $admin = Admin::factory()->create();
        $resident = User::factory()->create(['role' => 'resident']);
        
        Feedback::factory()->count(3)->create([
            'user_id' => $resident->id
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/feedback');

        $response->assertStatus(200);
        $response->assertViewIs('admin.feedback.index');
    }

    public function test_admin_can_view_individual_feedback(): void
    {
        $admin = Admin::factory()->create();
        $resident = User::factory()->create(['role' => 'resident']);
        
        $feedback = Feedback::factory()->create([
            'user_id' => $resident->id,
            'subject' => 'Test Feedback',
            'message' => 'This is a test feedback message'
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/feedback/{$feedback->id}");

        $response->assertStatus(200);
        $response->assertViewIs('admin.feedback.show');
        $response->assertViewHas('feedback', $feedback);
        $response->assertSee('Test Feedback');
        $response->assertSee('This is a test feedback message');
    }

    public function test_admin_can_respond_to_feedback(): void
    {
        $admin = Admin::factory()->create();
        $resident = User::factory()->create(['role' => 'resident']);
        
        $feedback = Feedback::factory()->create([
            'user_id' => $resident->id,
            'admin_response' => null
        ]);

        $response = $this->actingAs($admin, 'admin')->post("/admin/feedback/{$feedback->id}/respond", [
            'admin_response' => 'Thank you for your feedback. We will look into this matter.'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('feedback', [
            'id' => $feedback->id,
            'admin_response' => 'Thank you for your feedback. We will look into this matter.',
            'admin_responded_by' => $admin->id
        ]);
    }

    public function test_admin_can_mark_feedback_as_resolved(): void
    {
        $admin = Admin::factory()->create();
        $resident = User::factory()->create(['role' => 'resident']);
        
        $feedback = Feedback::factory()->create([
            'user_id' => $resident->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($admin, 'admin')->post("/admin/feedback/{$feedback->id}/resolve");

        $response->assertRedirect();
        $this->assertDatabaseHas('feedback', [
            'id' => $feedback->id,
            'status' => 'resolved'
        ]);
    }

    public function test_admin_can_export_feedback_data(): void
    {
        $admin = Admin::factory()->create();
        $resident = User::factory()->create(['role' => 'resident']);
        
        Feedback::factory()->count(5)->create([
            'user_id' => $resident->id
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/feedback/export');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $expectedFilename = 'feedback_export_' . now()->format('Y_m_d') . '.csv';
        $response->assertHeader('content-disposition', 'attachment; filename="' . $expectedFilename . '"');
    }

    public function test_admin_can_filter_feedback_by_status(): void
    {
        $admin = Admin::factory()->create();
        $resident = User::factory()->create(['role' => 'resident']);
        
        Feedback::factory()->create([
            'user_id' => $resident->id,
            'status' => 'pending'
        ]);
        Feedback::factory()->create([
            'user_id' => $resident->id,
            'status' => 'resolved'
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/feedback?status=pending');

        $response->assertStatus(200);
    }

    public function test_admin_can_filter_feedback_by_type(): void
    {
        $admin = Admin::factory()->create();
        $resident = User::factory()->create(['role' => 'resident']);
        
        Feedback::factory()->create([
            'user_id' => $resident->id,
            'type' => 'complaint'
        ]);
        Feedback::factory()->create([
            'user_id' => $resident->id,
            'type' => 'suggestion'
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/feedback?type=complaint');

        $response->assertStatus(200);
    }

    public function test_admin_can_search_feedback(): void
    {
        $admin = Admin::factory()->create();
        $resident = User::factory()->create(['role' => 'resident']);
        
        Feedback::factory()->create([
            'user_id' => $resident->id,
            'subject' => 'Slow collection service',
            'message' => 'The collection is taking too long'
        ]);
        Feedback::factory()->create([
            'user_id' => $resident->id,
            'subject' => 'Great service',
            'message' => 'Very satisfied with the service'
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/feedback?search=slow');

        $response->assertStatus(200);
        $response->assertSee('Slow collection service');
        $response->assertDontSee('Great service');
    }

    public function test_feedback_response_requires_valid_data(): void
    {
        $admin = Admin::factory()->create();
        $resident = User::factory()->create(['role' => 'resident']);
        
        $feedback = Feedback::factory()->create([
            'user_id' => $resident->id
        ]);

        $response = $this->actingAs($admin, 'admin')->post("/admin/feedback/{$feedback->id}/respond", [
            'admin_response' => '' // Empty response
        ]);

        $response->assertSessionHasErrors(['admin_response']);
    }

    public function test_admin_can_view_feedback_with_related_report(): void
    {
        $admin = Admin::factory()->create();
        $resident = User::factory()->create(['role' => 'resident']);
        $report = WasteReport::factory()->create([
            'resident_id' => $resident->id
        ]);
        
        $feedback = Feedback::factory()->create([
            'user_id' => $resident->id,
            'waste_report_id' => $report->id
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/feedback/{$feedback->id}");

        $response->assertStatus(200);
        $response->assertViewHas('feedback');
        $this->assertEquals($report->id, $response->viewData('feedback')->waste_report_id);
    }

    public function test_feedback_statistics_are_calculated(): void
    {
        $admin = Admin::factory()->create();
        $resident = User::factory()->create(['role' => 'resident']);
        
        // Create feedback with different statuses and types
        Feedback::factory()->create([
            'user_id' => $resident->id,
            'status' => 'pending',
            'type' => 'complaint'
        ]);
        Feedback::factory()->create([
            'user_id' => $resident->id,
            'status' => 'resolved',
            'type' => 'suggestion'
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/feedback');

        $response->assertStatus(200);
        // Should include statistics about feedback counts, types, etc.
    }
}
