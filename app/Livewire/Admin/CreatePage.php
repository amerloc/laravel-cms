<?php

namespace App\Livewire\Admin;

use App\Models\Page;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class CreatePage extends Component
{
    public string $title = '';
    public string $slug = '';
    public string $layout = 'layouts.app';
    public string $format = 'html';
    public ?string $excerpt = null;
    public string $content = '';
    public ?string $meta_title = null;
    public ?string $meta_description = null;
    public ?string $meta_keywords = null;
    public ?string $canonical_url = null;
    public ?string $og_image_url = null;
    public bool $noindex = false;
    public string $status = 'draft';
    public ?string $published_at = null;

    public array $allowedLayouts = ['layouts.app', 'layouts.admin'];

    public function updatedTitle(): void
    {
        $this->slug = Str::slug($this->title);
    }

    public function save(): void
    {
        $data = $this->validate([
            'title' => 'required|string|max:255|unique:pages,title',
            'layout' => 'required|string|max:255',
            'format' => 'required|in:blade,markdown,html',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url',
            'og_image_url' => 'nullable|url',
            'noindex' => 'boolean',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        // Generate slug from title
        $data['slug'] = Str::slug($this->title);

        // Enforce allowed layouts
        if (!in_array($data['layout'], $this->allowedLayouts, true)) {
            $this->addError('layout', 'Invalid layout.');
            return;
        }

        $data['published_at'] = $data['published_at'] ? now()->parse($data['published_at']) : null;

        $page = Page::create($data + [
            'created_by_id' => auth()->id(),
            'updated_by_id' => auth()->id(),
        ]);

        session()->flash('status', 'Page created successfully!');
        
        $this->redirect(route('admin.pages.edit', $page));
    }

    public function render()
    {
        return view('livewire.admin.create-page');
    }
}