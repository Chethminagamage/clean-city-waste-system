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
            $resident = User::where('role', 'resident')->first();
            if (!$resident) {
                $this->markTestSkipped('No resident user found');
            }
            
            $browser->loginAs($resident)
                ->visit('/resident/dashboard')
                ->assertSee('Hello');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function resident_can_access_dashboard_using_loginAs()
    {
        $this->browse(function (Browser $browser) {
            $resident = User::where('role', 'resident')->first();
            if (!$resident) {
                $this->markTestSkipped('No resident user found');
            }
            
            $browser->loginAs($resident)
                ->visit('/resident/dashboard')
                ->assertSee('Hello');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function resident_can_access_reports_page()
    {
        $this->browse(function (Browser $browser) {
            $resident = User::where('role', 'resident')->first();
            if (!$resident) {
                $this->markTestSkipped('No resident user found');
            }
            
            $browser->loginAs($resident)
                ->visit('/resident/reports')
                ->assertSee('Report History');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function resident_can_access_profile_edit()
    {
        $this->browse(function (Browser $browser) {
            $resident = User::where('role', 'resident')->first();
            if (!$resident) {
                $this->markTestSkipped('No resident user found');
            }
            
            $browser->loginAs($resident)
                ->visit('/resident/profile/edit')
                ->assertSee('Profile Settings');
        });
    }

}