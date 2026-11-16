<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

// Proxy ke Python FastMCP Server internal (port 8001)
Route::any('/sse', function (Request $request) {
    $mcpHost = env('MCP_INTERNAL_URL', 'http://127.0.0.1:8001');
    $url = $mcpHost . '/sse';

    $response = Http::withHeaders($request->headers->all())
        ->send($request->method(), $url, [
            'query' => $request->query(),
            'body'  => $request->getContent(),
        ]);

    return response($response->body(), $response->status(), $response->headers());
});

Route::any('/messages', function (Request $request) {
    $mcpHost = env('MCP_INTERNAL_URL', 'http://127.0.0.1:8001');
    $url = $mcpHost . '/messages';

    $response = Http::withHeaders($request->headers->all())
        ->send($request->method(), $url, [
            'query' => $request->query(),
            'body'  => $request->getContent(),
        ]);

    return response($response->body(), $response->status(), $response->headers());
});
