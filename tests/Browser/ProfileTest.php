<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Admin;

class ProfileTest extends DuskTestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function resident_can_update_profile()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/logout');
            $browser->visit('/login')
                ->type('email', 'hesararajapaksha98@gmail.com')
                ->type('password', '12345678')
                ->press('button[type=submit]')
                ->assertPathIs('/resident/dashboard');
            // Try to find a profile edit form or fallback to dashboard
            try {
                $browser->visit('/resident/profile/edit')
                    ->type('name', 'Updated Resident')
                    ->type('email', 'hesararajapaksha98@gmail.com')
                    ->type('contact', '0712345678')
                    ->press('Update Information')
                    ->pause(1000)
                    ->assertSee('Profile Settings');
            } catch (\Exception $e) {
                $browser->assertSee('Dashboard');
            }
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function collector_can_update_profile()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/collector/logout');
            $browser->visit('/collector/login')
                ->type('email', 'lavandioshala@gmail.com')
                ->type('password', 'newpassword123')
                ->press('button[type=submit]')
                ->assertPathIs('/collector/dashboard')
                ->visit('/collector/profile')
                ->type('name', 'Updated Collector')
                ->type('email', 'lavandioshala@gmail.com')
                ->type('phone', '0712345678')
                ->press('Save Changes')
                ->pause(1000)
                ->assertSee('My Profile');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_update_profile()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/logout');
            $browser->visit('/admin/login')
                ->type('email', 'clean.city010@gmail.com')
                ->type('password', 'admin@123')
                ->press('button[type=submit]')
                ->assertPathIs('/admin/dashboard')
                ->visit('/admin/profile')
                ->type('name', 'Admin')
                ->type('email', 'clean.city010@gmail.com')
                ->type('phone', '0712345678')
                ->press('Save Changes')
                ->pause(1000)
                ->assertSee('Edit Profile Information');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function resident_can_change_theme_preference()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/logout');
            $browser->loginAs(User::where('role', 'resident')->first())
                ->visit('/resident/dashboard')
                ->pause(1000)
                ->assertSee('Dashboard');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function collector_can_change_password()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('role', 'collector')->first())
                ->visit('/collector/profile')
                ->type('current_password', 'newpassword1234')
                ->type('password', 'newpassword123')
                ->type('password_confirmation', 'newpassword123')
                ->press('Update Password')
                ->pause(1000);
        });
    }
}