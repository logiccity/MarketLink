<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AdminPortalTest extends TestCase
{
    protected function getAdminUser(): User
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::factory()->create(['role' => 'admin']);
        }
        return $admin;
    }

    public function test_admin_dashboard_renders_successfully(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('MarketLink Operations Hub');
        $response->assertSee('Gross Volume (GMV)');
        $response->assertSee('Harvest Pre-Orders');
    }

    public function test_admin_dashboard_with_date_range_filters(): void
    {
        $admin = $this->getAdminUser();

        foreach (['today', '7d', '30d', 'month', 'year', 'all'] as $range) {
            $response = $this->actingAs($admin)->get('/admin/dashboard?range=' . $range);
            $response->assertStatus(200);
        }
    }

    public function test_admin_global_search_works(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/search?q=green');
        $response->assertStatus(200);
        $response->assertSee('Global System Search');
    }

    public function test_admin_notifications_center_works(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/notifications');
        $response->assertStatus(200);
        $response->assertSee('Notifications Center');
    }

    public function test_admin_orders_index_works(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/orders');
        $response->assertStatus(200);
    }

    public function test_admin_farmers_index_works(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/farmers');
        $response->assertStatus(200);
    }

    public function test_admin_customers_index_works(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/customers');
        $response->assertStatus(200);
    }

    public function test_admin_markets_index_works(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/markets');
        $response->assertStatus(200);
    }

    public function test_admin_products_index_works(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/products');
        $response->assertStatus(200);
    }

    public function test_admin_reports_index_works(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/reports');
        $response->assertStatus(200);
    }
}
