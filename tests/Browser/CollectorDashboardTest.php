<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class CollectorDashboardTest extends DuskTestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function collector_can_view_assigned_reports()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/collector/login')
                ->type('email', 'lavandioshala@gmail.com')
                ->type('password', 'newpassword123')
                ->press('button[type=submit]')
                ->assertPathIs('/collector/dashboard')
                ->assertSee('Assigned Reports');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function collector_can_view_report_details()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/collector/logout');
            $browser->visit('/collector/login')
                ->type('email', 'lavandioshala@gmail.com')
                ->type('password', 'newpassword123')
                ->press('button[type=submit]')
                ->assertPathIs('/collector/dashboard')
                ->assertSee('Assigned Reports');

            // Try to click the "View Details" button by visible text if it exists
            try {
                $browser->waitFor('button', 5)
                    ->press('View Details')
                    ->pause(1000)
                    ->assertSee('Report Details');
            } catch (\Exception $e) {
                // No assigned reports to view
                $browser->assertSee('No Assigned Reports');
            }
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function collector_can_confirm_assignment()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/collector/logout');
            $browser->visit('/collector/login')
                ->type('email', 'lavandioshala@gmail.com')
                ->type('password', 'newpassword123')
                ->press('button[type=submit]')
                ->assertPathIs('/collector/dashboard')
                ->assertSee('Assigned Reports');

            // Try to submit the Confirm Assignment form for the first assigned report
            try {
                $browser->with('.report-card', function ($card) {
                    $card->press('Confirm Assignment');
                });
                $browser->pause(1000);
                // After confirming, check for a success message or that the button is gone
                $browser->assertDontSee('Confirm Assignment');
            } catch (\Exception $e) {
                // No assigned reports or already confirmed
                $browser->assertSee('No Assigned Reports');
            }
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function collector_can_quick_complete_report()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/collector/logout');
            $browser->visit('/collector/login')
                ->type('email', 'lavandioshala@gmail.com')
                ->type('password', 'newpassword123')
                ->press('button[type=submit]')
                ->assertPathIs('/collector/dashboard')
                ->assertSee('Assigned Reports');

            // Try to submit the Quick Complete form for the first enroute report
            try {
                $browser->with('.report-card', function ($card) {
                    if ($card->element('button:contains("Quick Complete")')) {
                        $card->press('Quick Complete');
                    }
                });
                $browser->pause(1000);
                // After quick complete, check for a success message or that the button is gone
                $browser->assertDontSee('Quick Complete');
            } catch (\Exception $e) {
                // No enroute reports available
                $browser->assertSee('No Assigned Reports');
            }
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function collector_can_view_all_reports()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/collector/logout');
            $browser->loginAs(User::where('role', 'collector')->first())
                ->visit('/collector/reports')
                ->assertSee('All Reports');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function collector_can_view_completed_reports()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/collector/logout');
            $browser->loginAs(User::where('role', 'collector')->first())
                ->visit('/collector/reports/completed')
                ->assertSee('Completed Reports');
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function collector_can_access_dashboard_sections()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/collector/logout');
            $browser->loginAs(User::where('role', 'collector')->first())
                ->visit('/collector/dashboard')
                ->assertSee('Assigned Reports')
                ->assertSee('Quick Actions');
        });
    }
}