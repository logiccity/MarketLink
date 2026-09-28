<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{
    /**
     * Display the Platform Configuration & Settings dashboard.
     */
    public function index()
    {
        $settings = [
            'platform_name'                 => Setting::get('platform_name', 'eGreen Basket MarketLink'),
            'platform_tagline'              => Setting::get('platform_tagline', 'Connecting Local Growers with Conscious Patrons'),
            'contact_email'                 => Setting::get('contact_email', 'support@egreenbasket.com'),
            'contact_phone'                 => Setting::get('contact_phone', '+92 300 1234567'),
            'currency_code'                 => Setting::get('currency_code', 'PKR'),
            'currency_symbol'               => Setting::get('currency_symbol', 'PKR '),
            'default_cutoff_hours'          => Setting::get('default_cutoff_hours', '12'),
            'max_advance_days'              => Setting::get('max_advance_days', '14'),
            'auto_cancel_unconfirmed_hours' => Setting::get('auto_cancel_unconfirmed_hours', '6'),
            'min_order_amount'              => Setting::get('min_order_amount', '0'),
            'require_grower_approval'       => Setting::get('require_grower_approval', '1'),
            'require_review_moderation'     => Setting::get('require_review_moderation', '0'),
            'allow_farmer_review_replies'   => Setting::get('allow_farmer_review_replies', '1'),
            'enable_restock_alerts'         => Setting::get('enable_restock_alerts', '1'),
        ];

        $systemInfo = [
            'php_version'       => PHP_VERSION,
            'laravel_version'   => app()->version(),
            'server_os'         => PHP_OS,
            'database_driver'   => DB::connection()->getDriverName(),
            'database_version'  => DB::connection()->getPdo()->getAttribute(\PDO::ATTR_SERVER_VERSION),
            'environment'       => app()->environment(),
            'debug_mode'        => config('app.debug') ? 'Enabled (Development)' : 'Disabled (Production)',
            'timezone'          => config('app.timezone', 'UTC'),
        ];

        return view('admin.config.index', compact('settings', 'systemInfo'));
    }

    /**
     * Update platform settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'platform_name'                 => ['required', 'string', 'max:255'],
            'platform_tagline'              => ['nullable', 'string', 'max:500'],
            'contact_email'                 => ['required', 'email', 'max:255'],
            'contact_phone'                 => ['required', 'string', 'max:50'],
            'currency_code'                 => ['required', 'string', 'max:10'],
            'currency_symbol'               => ['required', 'string', 'max:10'],
            'default_cutoff_hours'          => ['required', 'integer', 'min:1', 'max:168'],
            'max_advance_days'              => ['required', 'integer', 'min:1', 'max:60'],
            'auto_cancel_unconfirmed_hours' => ['required', 'integer', 'min:1', 'max:72'],
            'min_order_amount'              => ['required', 'numeric', 'min:0'],
            'require_grower_approval'       => ['nullable', 'boolean'],
            'require_review_moderation'     => ['nullable', 'boolean'],
            'allow_farmer_review_replies'   => ['nullable', 'boolean'],
            'enable_restock_alerts'         => ['nullable', 'boolean'],
        ]);

        Setting::set('platform_name', $validated['platform_name'], 'general');
        Setting::set('platform_tagline', $validated['platform_tagline'] ?? '', 'general');
        Setting::set('contact_email', $validated['contact_email'], 'general');
        Setting::set('contact_phone', $validated['contact_phone'], 'general');
        Setting::set('currency_code', $validated['currency_code'], 'general');
        Setting::set('currency_symbol', $validated['currency_symbol'], 'general');

        Setting::set('default_cutoff_hours', $validated['default_cutoff_hours'], 'ordering');
        Setting::set('max_advance_days', $validated['max_advance_days'], 'ordering');
        Setting::set('auto_cancel_unconfirmed_hours', $validated['auto_cancel_unconfirmed_hours'], 'ordering');
        Setting::set('min_order_amount', $validated['min_order_amount'], 'ordering');

        Setting::set('require_grower_approval', $request->has('require_grower_approval') ? '1' : '0', 'moderation');
        Setting::set('require_review_moderation', $request->has('require_review_moderation') ? '1' : '0', 'moderation');
        Setting::set('allow_farmer_review_replies', $request->has('allow_farmer_review_replies') ? '1' : '0', 'moderation');
        Setting::set('enable_restock_alerts', $request->has('enable_restock_alerts') ? '1' : '0', 'moderation');

        return back()->with('success', 'Platform settings and operational parameters updated successfully.');
    }

    /**
     * Clear application, route, and view caches.
     */
    public function clearCache(Request $request)
    {
        $type = $request->input('type', 'all');

        try {
            $msg = match ($type) {
                'routes' => tap('Route cache cleared successfully.', fn() => Artisan::call('route:clear')),
                'views'  => tap('Compiled Blade templates cleared successfully.', fn() => Artisan::call('view:clear')),
                'config' => tap('Configuration cache cleared successfully.', fn() => Artisan::call('config:clear')),
                default  => tap('All framework caches cleared successfully.', fn() => Artisan::call('optimize:clear')),
            };

            return back()->with('success', $msg);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }
}
