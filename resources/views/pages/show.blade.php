<article class="prose max-w-3xl mx-auto py-10">
    <header class="mb-6">
        <h1 class="!mb-2">{{ $page->title }}</h1>
        @if($page->excerpt)
            <p class="text-gray-600">{{ $page->excerpt }}</p>
        @endif
    </header>

    {!! $content !!} {{-- already sanitized/rendered in component --}}
</article>
