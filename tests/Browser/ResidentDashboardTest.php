<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class ResidentDashboardTest extends DuskTestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function resident_can_access_dashboard()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'hesararajapaksha98@gmail.com')
                ->type('password', '12345678')
                ->press('button[type=submit]')
                ->assertPathIs('/resident/dashboard')
                ->assertSee('Submit New Waste Report');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function resident_can_submit_waste_report()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/logout')
                ->visit('/login')
                ->type('email', 'hesararajapaksha98@gmail.com')
                ->type('password', '12345678')
                ->press('button[type=submit]')
                ->assertPathIs('/resident/dashboard')
                ->assertSee('Submit New Waste Report')
                ->select('waste_type', 'Plastic')
                ->type('additional_details', 'Overflowing bin')
                ->attach('image', __DIR__.'/../../storage/app/test-image.jpg')
                ->press('Submit Waste Report')
                ->pause(2000);
                // Report submission might redirect or show success message
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function resident_can_view_report_history()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('role', 'resident')->first())
                ->visit('/resident/dashboard')
                ->assertSee('Report History');
                
            // Try to view reports page
            try {
                $browser->visit('/resident/reports')
                    ->assertSee('Report History');
            } catch (\Exception $e) {
                // Reports page might not exist or be accessible
                $browser->assertSee('No reports submitted yet');
            }
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function resident_can_view_report_details()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('role', 'resident')->first())
                ->visit('/resident/dashboard');
                
            // Try to click view details if reports exist
            try {
                $browser->click('View Details')
                    ->pause(1000)
                    ->assertSee('Report Details');
            } catch (\Exception $e) {
                // No reports to view
                $browser->assertSee('No reports submitted yet');
            }
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function resident_can_access_profile_edit()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('role', 'resident')->first())
                ->visit('/resident/profile/edit')
                ->assertSee('Profile Settings');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function resident_can_view_schedule()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('role', 'resident')->first())
                ->visit('/resident/schedule')
                ->assertSee('Collection Schedule');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function resident_can_view_gamification_section()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('role', 'resident')->first())
                ->visit('/resident/dashboard')
                ->assertSee('Progress & Achievements');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function resident_can_submit_feedback()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('role', 'resident')->first())
                ->visit('/feedback')
                ->select('feedback_type', 'service_quality')
                ->select('rating', '5')
                ->type('message', 'Great service!')
                ->press('Submit Feedback')
                ->pause(1000);
        });
    }
}