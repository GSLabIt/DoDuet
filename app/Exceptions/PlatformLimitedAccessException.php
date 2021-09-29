<?php

namespace App\Exceptions;

use App\Models\Platforms;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Inertia\Response;
use Inertia\ResponseFactory;
use JetBrains\PhpStorm\Pure;
use Throwable;

class PlatformLimitedAccessException extends Exception
{
    private array $whitelisted_domains = [
        "localhost",
        "127.0.0.1",
    ];

    public function __construct(private Platforms $platform, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->whitelisted_domains[] = env("NETWORK_IP", "invalid-ip");
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @return Application|RedirectResponse|Redirector|Response|ResponseFactory
     */
    public function render($request): Application|RedirectResponse|Redirector|Response|ResponseFactory
    {
        if($this->platform->is_password_protected) {
            return inertia("Errors/PasswordProtectedPlatform");
        }
        elseif(Arr::hasAny($this->whitelisted_domains, $request->getHttpHost())) {
            $platform_name = config("platforms.platform_name_parser")(config("platforms.platform_name"));
            $session_value = Str::replace("{{ platform_name }}", $platform_name, config("platforms.session_value"));

            // if coming from whitelisted domains authenticate without password
            session()->push($session_value, true);

            // then redirect to requested page
            return redirect($request->getRequestUri());
        }
    }
}
