<div class="space-y-6">
    @if (session('status'))
        <div class="rounded-md bg-green-100 p-3 text-green-800">{{ session('status') }}</div>
    @endif

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">Create New Page</h1>
        <a href="{{ route('admin.pages.index') }}" class="text-gray-600 hover:text-gray-900">← Back to Pages</a>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" wire:model.live="title" placeholder="Enter page title">
                    @error('title') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Layout</label>
                        <select class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" wire:model.live="layout">
                            @foreach($allowedLayouts as $layout)
                                <option value="{{ $layout }}">{{ $layout }}</option>
                            @endforeach
                        </select>
                        @error('layout') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Format</label>
                        <select class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" wire:model.live="format">
                            <option value="html">HTML</option>
                            <option value="markdown">Markdown</option>
                            <option value="blade">Blade</option>
                        </select>
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-md p-3">
                    <label class="block text-sm font-medium text-gray-700">Slug (auto-generated)</label>
                    <div class="mt-1 text-sm text-gray-600 font-mono bg-white border border-gray-300 rounded px-3 py-2">{{ $slug }}</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Excerpt (optional)</label>
                    <textarea class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" rows="2" wire:model.live="excerpt" placeholder="Brief description of the page"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-mono" rows="16" wire:model.live="content" placeholder="Enter your page content here..."></textarea>
                    @error('content') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="space-y-4">
                <div class="border border-gray-200 rounded-lg p-4 space-y-3">
                    <h3 class="font-semibold text-gray-900">SEO Settings</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Meta Title</label>
                        <input type="text" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" wire:model.live="meta_title" placeholder="SEO title">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Meta Description</label>
                        <textarea class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" rows="3" wire:model.live="meta_description" placeholder="SEO description"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Meta Keywords</label>
                        <input type="text" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" wire:model.live="meta_keywords" placeholder="keyword1, keyword2, keyword3">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Canonical URL</label>
                        <input type="url" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" wire:model.live="canonical_url" placeholder="https://example.com/page">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">OG Image URL</label>
                        <input type="url" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" wire:model.live="og_image_url" placeholder="https://example.com/image.jpg">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="noindex" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" wire:model.live="noindex">
                        <label for="noindex" class="ml-2 block text-sm text-gray-700">Noindex (prevent search engines from indexing)</label>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-lg p-4 space-y-3">
                    <h3 class="font-semibold text-gray-900">Publishing</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" wire:model.live="status">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Published At</label>
                        <input type="datetime-local" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" wire:model.live="published_at">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Create Page
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>