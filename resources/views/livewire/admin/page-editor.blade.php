<div class="space-y-6">
    @if (session('status'))
        <div class="rounded-md bg-green-100 p-4 text-green-800 border border-green-200">{{ session('status') }}</div>
    @endif

    @if (session('warning'))
        <div class="rounded-md bg-yellow-100 p-4 text-yellow-800 border border-yellow-200">{{ session('warning') }}</div>
    @endif

    <!-- Header with Actions -->
    <div class="flex items-center justify-between border-b border-gray-200 pb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                @if($page)
                    Edit Page: {{ $page->title }}
                @else
                    Create New Page
                @endif
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                @if($page)
                    Last updated {{ $page->updated_at->diffForHumans() }}
                @else
                    Create a new page for your website
                @endif
            </p>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex items-center space-x-3">
            <button wire:click="save" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                Save Page
            </button>
            
            @if($page)
                <a href="{{ route('admin.pages.builder', $page) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                    </svg>
                    Visual Builder
                </a>
                
                <a href="{{ route('page.show', ['slug' => $page->slug]) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Preview
                </a>
            @endif
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <button wire:click="$set('activeTab', 'content')" 
                    class="py-3 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'content' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Content
            </button>
            <button wire:click="$set('activeTab', 'details')" 
                    class="py-3 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'details' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Details
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="bg-white shadow-sm border border-gray-200 rounded-lg">
        @if($activeTab === 'content')
            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Page Content</label>
                    <textarea class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm font-mono" rows="20" wire:model.live="content" placeholder="Enter your page content here..."></textarea>
                    <p class="mt-2 text-sm text-gray-500">Write your page content using the selected format below.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Layout Template</label>
                        <select class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" wire:model.live="layout">
                            @foreach($allowedLayouts as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </select>
                        @error('layout') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        <p class="mt-2 text-sm text-gray-500">Choose the layout template for this page.</p>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">Content Format</label>
                            <button type="button" wire:click="openFormatModal" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Learn more
                            </button>
                        </div>
                        <select class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" wire:model.live="format">
                            <option value="html">HTML</option>
                            <option value="markdown">Markdown</option>
                            <option value="blade">Blade Template</option>
                        </select>
                        <p class="mt-2 text-sm text-gray-500">Select how your content should be processed.</p>
                    </div>
                </div>
            </div>
        @elseif($activeTab === 'details')
            <div class="p-6 space-y-8">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Page Title</label>
                            <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" wire:model.live="title" placeholder="Enter page title...">
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">URL Slug</label>
                            <div class="mt-1 text-sm text-gray-600 font-mono bg-white border border-gray-300 rounded px-3 py-2">{{ $slug }}</div>
                            <p class="mt-2 text-sm text-gray-500">This is automatically generated from your title and used in the page URL.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
                            <textarea class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" rows="3" wire:model.live="excerpt" placeholder="Brief description of this page..."></textarea>
                            <p class="mt-2 text-sm text-gray-500">A short summary that may be used in listings and search results.</p>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                            <input class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="SEO title for search engines" wire:model.live="meta_title">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" rows="3" placeholder="Description for search engine results" wire:model.live="meta_description"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                            <input class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Keywords separated by commas" wire:model.live="meta_keywords">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Canonical URL</label>
                                <input class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="https://example.com/page" wire:model.live="canonical_url">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Open Graph Image</label>
                                <input class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="https://example.com/image.jpg" wire:model.live="og_image_url">
                            </div>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" wire:model.live="noindex">
                            <label class="ml-2 block text-sm text-gray-700">
                                Prevent search engines from indexing this page
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Publishing Settings -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Publishing</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" wire:model.live="status">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Publish Date</label>
                            <input type="datetime-local" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" wire:model.live="published_at">
                            <p class="mt-2 text-sm text-gray-500">Leave empty to publish immediately when status is set to Published.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Format Information Modal -->
    @if($showFormatModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeFormatModal">
            <div class="relative top-4 mx-auto p-4 border w-11/12 md:w-3/4 lg:w-2/3 xl:w-1/2 max-w-4xl shadow-lg rounded-md bg-white mb-4" >
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Content Format Options</h3>
                    <button wire:click="closeFormatModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="max-h-[70vh] overflow-y-auto pr-2">
            <div class="space-y-6">
                <!-- HTML Format -->
                <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-3">
                        <div class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full mr-3">HTML</div>
                        <h4 class="text-lg font-semibold text-gray-900">HTML Format</h4>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">
                        Write your content using standard HTML tags. This format gives you complete control over styling and structure.
                    </p>
                    <div class="mb-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Best for:</h5>
                        <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
                            <li>Full control over styling and layout</li>
                            <li>Complex page structures</li>
                            <li>Custom HTML elements and attributes</li>
                            <li>Integration with CSS frameworks</li>
                        </ul>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h5 class="text-xs font-medium text-gray-700 mb-2">Example:</h5>
                        <pre class="text-xs font-mono text-gray-700 whitespace-pre-wrap break-words"><code>&lt;h1&gt;Welcome to Our Site&lt;/h1&gt;
&lt;p&gt;This is a paragraph with &lt;strong&gt;bold text&lt;/strong&gt;.&lt;/p&gt;
&lt;div class="custom-section"&gt;
    &lt;h2&gt;Custom Section&lt;/h2&gt;
    &lt;ul&gt;
        &lt;li&gt;List item 1&lt;/li&gt;
        &lt;li&gt;List item 2&lt;/li&gt;
    &lt;/ul&gt;
&lt;/div&gt;</code></pre>
                    </div>
                </div>

                <!-- Markdown Format -->
                <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-3">
                        <div class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full mr-3">MARKDOWN</div>
                        <h4 class="text-lg font-semibold text-gray-900">Markdown Format</h4>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">
                        Write your content using Markdown syntax. Simple, readable, and automatically converted to HTML.
                    </p>
                    <div class="mb-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Best for:</h5>
                        <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
                            <li>Blog posts and articles</li>
                            <li>Simple, readable content</li>
                            <li>Quick formatting without HTML knowledge</li>
                            <li>Documentation and guides</li>
                        </ul>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h5 class="text-xs font-medium text-gray-700 mb-2">Example:</h5>
                        <pre class="text-xs font-mono text-gray-700 whitespace-pre-wrap break-words"><code># Welcome to Our Site

This is a paragraph with **bold text** and *italic text*.

## Features

- Easy to read and write
- Automatic HTML conversion
- Great for content creators

> This is a blockquote</code></pre>
                    </div>
                </div>

                <!-- Blade Format -->
                <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-3">
                        <div class="bg-purple-100 text-purple-800 text-xs font-medium px-3 py-1 rounded-full mr-3">BLADE</div>
                        <h4 class="text-lg font-semibold text-gray-900">Blade Template Format</h4>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">
                        Write your content using Laravel Blade template syntax. Access to PHP variables, conditionals, and loops.
                    </p>
                    <div class="mb-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Best for:</h5>
                        <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
                            <li>Dynamic content with PHP variables</li>
                            <li>Conditional logic and loops</li>
                            <li>Advanced Laravel features</li>
                            <li>Template inheritance and components</li>
                        </ul>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h5 class="text-xs font-medium text-gray-700 mb-2">Example:</h5>
                        <pre class="text-xs font-mono text-gray-700 whitespace-pre-wrap break-words"><code>&lt;h1&gt;{{ $page->title }}&lt;/h1&gt;

@if($page->excerpt)
    &lt;p class="excerpt"&gt;{{ $page->excerpt }}&lt;/p&gt;
@endif

@if($page->status === 'published')
    &lt;div class="published-content"&gt;
        &lt;p&gt;This page is live and visible to visitors.&lt;/p&gt;
    &lt;/div&gt;
@else
    &lt;div class="draft-notice"&gt;
        &lt;p&gt;This page is currently in draft mode.&lt;/p&gt;
    &lt;/div&gt;
@endif

&lt;p&gt;Last updated: {{ $page->updated_at->format('M j, Y') }}&lt;/p&gt;</code></pre>
                    </div>
                </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-end">
                    <button wire:click="closeFormatModal" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Got it
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>