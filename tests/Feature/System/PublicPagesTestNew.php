<?php

namespace Tests\Feature\System;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Clean City');
        $response->assertSee('Waste Management');
    }

    public function test_services_page_loads(): void
    {
        $response = $this->get('/services');

        $response->assertStatus(200);
        $response->assertSee('Services');
    }

    public function test_projects_page_loads(): void
    {
        $response = $this->get('/projects');

        $response->assertStatus(200);
        $response->assertSee('Projects');
    }

    public function test_company_page_loads(): void
    {
        $response = $this->get('/company');

        $response->assertStatus(200);
        $response->assertSee('Company');
    }

    public function test_blog_page_loads(): void
    {
        $response = $this->get('/blog');

        $response->assertStatus(200);
        $response->assertSee('Blog');
    }

    public function test_contact_page_loads(): void
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
        $response->assertSee('Contact');
    }

    public function test_all_public_pages_are_accessible(): void
    {
        $pages = ['/', '/services', '/projects', '/company', '/blog', '/contact'];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertStatus(200);
        }
    }

    public function test_public_pages_contain_expected_content(): void
    {
        // Test home page
        $response = $this->get('/');
        $response->assertSee('Clean City');
        $response->assertSee('Login');

        // Test services page
        $response = $this->get('/services');
        $response->assertSee('Services');

        // Test contact page
        $response = $this->get('/contact');
        $response->assertSee('Contact');
    }

    public function test_home_page_has_auth_links(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Login');
        $response->assertSee('Clean City');
        $response->assertSee('Schedule Pickup');
    }

    public function test_contact_page_has_contact_information(): void
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
        $response->assertSee('Contact');
    }

    public function test_navigation_links_are_present(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Home');
        $response->assertSee('Service');
        $response->assertSee('Projects');
        $response->assertSee('Company');
        $response->assertSee('Blog');
        $response->assertSee('Contact');
    }

    public function test_footer_contains_company_information(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Clean City');
        $response->assertSee('contact@cleancity.gmail.com');
        $response->assertSee('+94 81 456 7890');
    }

    public function test_pages_have_proper_meta_tags(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('<meta charset="UTF-8">', false);
        $response->assertSee('<meta name="viewport"', false);
    }
}
