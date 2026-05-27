<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Only scope for web guard/admin routes (avoid API or channel issues if any, but since it is simple web app, it is perfect)
            $activeTenantId = $this->getActiveTenantId();
            $activeTenant = \App\Models\Tenant::find($activeTenantId);
            $tenants = \App\Models\Tenant::all();

            view()->share('activeTenant', $activeTenant);
            view()->share('activeTenantId', $activeTenantId);
            view()->share('tenants', $tenants);

            return $next($request);
        });
    }

    /**
     * Get the active tenant ID from session, or default to the first tenant.
     */
    public function getActiveTenantId()
    {
        if (session()->has('active_tenant_id')) {
            $tenantId = session('active_tenant_id');
            if (\App\Models\Tenant::where('id', $tenantId)->exists()) {
                return $tenantId;
            }
        }

        $tenant = \App\Models\Tenant::first();
        if (!$tenant) {
            $tenant = \App\Models\Tenant::create(['name' => 'Main Campus']);
        }

        session(['active_tenant_id' => $tenant->id]);
        return $tenant->id;
    }
}
