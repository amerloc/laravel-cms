<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ ($meta['title'] ?? null) ? $meta['title'].' | Admin | '.config('app.name') : 'Admin | '.config('app.name') }}</title>

        @if(!empty($meta['description'])) <meta name="description" content="{{ $meta['description'] }}"> @endif
        @if(!empty($meta['keywords']))    <meta name="keywords" content="{{ $meta['keywords'] }}"> @endif
        @if(!empty($meta['canonical']))   <link rel="canonical" href="{{ $meta['canonical'] }}"> @endif
        @if(!empty($meta['og_image']))    <meta property="og:image" content="{{ $meta['og_image'] }}"> @endif
        @if(($meta['noindex'] ?? false))  <meta name="robots" content="noindex, nofollow"> @endif

        {{-- extra meta_json: [{"name":"twitter:card", "content":"summary_large_image"}] --}}
        @if(!empty($meta['extra']) && is_array($meta['extra']))
            @foreach($meta['extra'] as $pair)
                @if(isset($pair['name'],$pair['content']))
                    <meta name="{{ $pair['name'] }}" content="{{ $pair['content'] }}">
                @endif
            @endforeach
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @livewireStyles
        @vite(['resources/css/app.css'])
        
        @if(!empty($builder_inline_css))
        <style>{!! $builder_inline_css !!}</style>
        @endif
       
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen">
            <!-- Admin Navigation -->
            <nav class="bg-white shadow-sm border-b">
                <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <h1 class="text-xl font-semibold text-gray-900">Admin Panel</h1>
                            </div>
                            <div class="ml-10 flex items-baseline space-x-4">
                                <a href="{{ route('admin.pages.index') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Pages</a>
                                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                                <a href="/" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">View Site</a>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-500">{{ auth()->user()?->name ?? 'Admin' }}</span>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="max-w-9xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>
        </div>
        
        @livewireScripts
        @stack('scripts')
    </body>
</html>
