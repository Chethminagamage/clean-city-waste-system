<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class AuthTest extends DuskTestCase
{
    #[Test]
    public function resident_can_login()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'gamagechethmina2004@gmail.com')
                ->type('password', '12345678')
                ->press('button[type="submit"]')
                ->waitForLocation('/resident/dashboard', 5)
                ->assertPathIs('/resident/dashboard');
        });
    }

    #[Test]
    public function collector_can_login()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/collector/login')
                ->type('email', 'lavandioshala@gmail.com')
                ->type('password', 'newpassword1234')
                ->press('button[type="submit"]')
                ->waitForLocation('/collector/dashboard', 5)
                ->assertPathIs('/collector/dashboard');
        });
    }

    #[Test]
    public function admin_can_login()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'clean.city010@gmail.com')
                ->type('password', 'admin@123')
                ->press('button[type="submit"]')
                ->waitForLocation('/admin/dashboard', 5)
                ->assertPathIs('/admin/dashboard');
        });
    }
}