<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Admin;

class ProfileTest extends DuskTestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function resident_can_access_profile_page()
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

    #[\PHPUnit\Framework\Attributes\Test]
    public function collector_can_access_profile_page()
    {
        $this->browse(function (Browser $browser) {
            $collector = User::where('role', 'collector')->first();
            if (!$collector) {
                $this->markTestSkipped('No collector user found');
            }
            
            $browser->loginAs($collector, 'collector')
                ->visit('/collector/profile')
                ->assertSee('Profile');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_access_profile_page()
    {
        $this->browse(function (Browser $browser) {
            $admin = Admin::first();
            if (!$admin) {
                $this->markTestSkipped('No admin user found');
            }
            
            $browser->loginAs($admin, 'admin')
                ->visit('/admin/profile')
                ->assertSee('Profile');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function resident_can_login_and_access_dashboard()
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
    public function collector_can_login_and_access_dashboard()
    {
        $this->browse(function (Browser $browser) {
            $collector = User::where('role', 'collector')->first();
            if (!$collector) {
                $this->markTestSkipped('No collector user found');
            }
            
            $browser->loginAs($collector, 'collector')
                ->visit('/collector/dashboard')
                ->assertSee('Quick Actions');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_login_and_access_dashboard()
    {
        $this->browse(function (Browser $browser) {
            $admin = Admin::first();
            if (!$admin) {
                $this->markTestSkipped('No admin user found');
            }
            
            $browser->loginAs($admin, 'admin')
                ->visit('/admin/dashboard')
                ->assertSee('Dashboard');
        });
    }
}