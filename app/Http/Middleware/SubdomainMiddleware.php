<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubdomainMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the current host from the request
        $host = $request->getHost();

        // Extract the subdomain from the host
        // This assumes your main domain is something like "example.com"
        // and subdomains are like "tenant1.example.com"
        $parts = explode('.', $host);

        // Only process if we have a subdomain
        if (count($parts) > 2) {
            $subdomain = $parts[0];

            // Here you can do any tenant-specific logic based on the subdomain
            // For example, you might want to set a tenant ID in the session
            // or load tenant-specific configuration

            // Set the correct URL for the public disk to ensure file URLs use the current domain
            config()->set('filesystems.disks.public.url', url('/storage'));
            config()->set('filesystems.disks.pets.url', url('/storage/pets'));
            config()->set('filesystems.disks.products.url', url('/storage/products'));
            // You can also set other configurations if needed
            // For example, if you need specific tenant storage paths:
            // config()->set('filesystems.disks.public.root', storage_path("app/public/tenants/{$subdomain}"));
        }

        return $next($request);
    }
}
