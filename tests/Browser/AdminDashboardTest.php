<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Admin;

class AdminDashboardTest extends DuskTestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_manage_users_and_reports()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'clean.city010@gmail.com')
                ->type('password', 'admin@123')
                ->press('Sign In')
                ->assertPathIs('/admin/dashboard')
                ->visit('/admin/users')
                ->waitForText('User Management', 5)
                ->assertSee('User Management')
                ->visit('/admin/binreports')
                ->waitForText('Submitted Bin Reports', 5)
                ->assertSee('Submitted Bin Reports');
        });
    }




    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_manage_collectors()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(Admin::first())
                ->visit('/admin/collectors')
                ->assertSee('Collectors Management')
                ->script(["document.getElementById('addModal').classList.remove('hidden')"]);
            $browser->type('name', 'New Collector')
                ->type('email', 'newcollector@example.com')
                ->type('contact', '0712345678')
                ->type('location', 'Test Area')
                ->press('Create Collector');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_manage_reports()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(Admin::first())
                ->visit('/admin/binreports')
                ->assertSee('Submitted Bin Reports')
                ->clickLink('View')
                ->assertSee('Report Details');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_view_alerts()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(Admin::first())
                ->visit('/admin/alerts')
                ->assertSee('System Alerts');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_view_analytics()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(Admin::first())
                ->visit('/admin/analytics')
                ->assertSee('Collector Efficiency Analytics');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_update_profile()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(Admin::first())
                ->visit('/admin/profile')
                ->type('name', 'Updated Admin')
                ->type('email', 'clean.city010@gmail.com')
                ->type('phone', '0712345678')
                ->press('Save Changes');
        });
    }
    
}
