<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Session\Store;

class RedirectIfSessionExpired
{
    protected $session;

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    public function handle($request, Closure $next)
    {
        if (!Auth::check() && $this->session->has('lastActivityTime')) {
            $lastActivityTime = $this->session->get('lastActivityTime');
            $sessionTimeout = config('session.lifetime') * 60;

            if (time() - $lastActivityTime > $sessionTimeout) {
                $this->session->forget('lastActivityTime');
                return redirect()->to('/');
            }
        }

        $this->session->put('lastActivityTime', time());

        return $next($request);
    }
}
