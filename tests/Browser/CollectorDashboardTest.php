<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class CollectorDashboardTest extends DuskTestCase
{
   #[\PHPUnit\Framework\Attributes\Test]
   public function collector_can_login_and_view_dashboard()
   {
     $this->browse(function (Browser $browser) {
            $collector = User::where('email', 'lavandioshala@gmail.com')->first();
            if (!$collector) {
                $this->markTestSkipped('Collector user not found');
            }
            
            $browser->loginAs($collector, 'collector')
                ->visit('/collector/dashboard')
                ->assertSee('Quick Actions');
        });
   }

    #[\PHPUnit\Framework\Attributes\Test]
    public function collector_can_access_dashboard_directly()
    {
        $this->browse(function (Browser $browser) {
            $collector = User::where('email', 'lavandioshala@gmail.com')->first();
            
            $browser->loginAs($collector, 'collector')
                ->visit('/collector/dashboard')
                ->assertSee('Quick Actions');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function collector_can_view_all_reports_page()
    {
        $this->browse(function (Browser $browser) {
            $collector = User::where('email', 'lavandioshala@gmail.com')->first();
            
            $browser->loginAs($collector, 'collector')
                ->visit('/collector/reports')
                ->assertSee('All Reports');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function collector_can_view_completed_reports_page()
    {
        $this->browse(function (Browser $browser) {
            $collector = User::where('email', 'lavandioshala@gmail.com')->first();
            
            $browser->loginAs($collector, 'collector')
                ->visit('/collector/reports/completed')
                ->assertSee('Completed Reports');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function collector_can_access_profile_page()
    {
        $this->browse(function (Browser $browser) {
            $collector = User::where('email', 'lavandioshala@gmail.com')->first();
            
            $browser->loginAs($collector, 'collector')
                ->visit('/collector/profile')
                ->assertSee('Profile');
        });
    }

}