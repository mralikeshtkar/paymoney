<?php

namespace App\Http\Middleware;

use App\Http\Helpers\Common;
use Closure;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponse;


class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    use ApiResponse;
    protected $permission;

    public function __construct(Common $permission)
    {
        $this->permission = $permission;
    }

    public function handle($request, Closure $next, $permissions)
    {
        $prefix = str_replace('/', '', request()->route()->getPrefix());
        if ($prefix == Config::get('adminPrefix')) {
            $gaurd_type = \Auth::guard('admin')->user()->id;
        } else {
            $gaurd_type = \Auth::user()->id;
        }

        if ($this->permission->has_permission($gaurd_type, $permissions)) {
            return $next($request);
        } else {
            if (str_contains($prefix, 'apiv2')) {
                return $this->forbiddenResponse([], __("Unauthorized"));
            }
            return response()->view('admin.errors.404', [], 404);
        }
    }
}
