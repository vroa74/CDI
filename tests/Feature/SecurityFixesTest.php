<?php

use App\Models\User;
use Illuminate\Http\Request;

test('pdf reports require authentication', function (string $url): void {
    $this->get($url)->assertRedirect(route('login', absolute: false));
})->with([
    '/service-pdf/1',
    '/service-cal-pdf/1',
    '/service-details-pdf/1',
    '/inventory-pdf/1',
]);

test('login route uses the configured rate limiter', function (): void {
    $route = app('router')->getRoutes()->match(Request::create('/login', 'POST'));

    expect($route->gatherMiddleware())->toContain('throttle:login');
});

test('public responses include security headers', function (): void {
    $this->get('/login')
        ->assertHeader('Content-Security-Policy', "base-uri 'self'; frame-ancestors 'none'; object-src 'none'")
        ->assertHeader('Permissions-Policy', 'camera=(), geolocation=(), microphone=()')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('X-Frame-Options', 'DENY');
});

test('pdf reports require an administrator', function (string $url): void {
    $user = User::factory()->create([
        'tipo' => 3,
        'curp' => 'CURP00000000000001',
    ]);

    $this->actingAs($user)->get($url)->assertForbidden();
})->with([
    '/service-pdf/1',
    '/service-cal-pdf/1',
    '/service-details-pdf/1',
    '/inventory-pdf/1',
]);
