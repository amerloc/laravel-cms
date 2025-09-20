<?php

namespace App\Livewire\Admin;

use App\Models\Page;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')] // default admin layout
class PageEditor extends Component
{
    public ?Page $page = null;

    // Form state
    public string $title = '';
    public string $slug = '';
    public string $layout = 'layouts.app';
    public ?string $view = null;
    public string $format = 'blade'; // blade|markdown|html
    public ?string $excerpt = null;
    public string $content = '';

    public ?string $meta_title = null;
    public ?string $meta_description = null;
    public ?string $meta_keywords = null;
    public ?string $canonical_url = null;
    public ?string $og_image_url = null;
    public bool $noindex = false;
    public array $meta_json = [];

    public string $status = 'draft';
    public ?string $published_at = null; // string for input

    public string $activeTab = 'content'; // content|details
    public bool $showFormatModal = false;

    public array $allowedLayouts = ['layouts.app','layouts.admin'];

    protected $listeners = [
        'modal-closed' => 'closeFormatModal',
    ];

    public function mount(?Page $page = null): void
    {
        // If /admin/pages/{page?}
        $this->page = $page;

        if ($page) {
            $this->fill([
                'title'            => $page->title,
                'slug'             => $page->slug,
                'layout'           => $page->layout,
                'view'             => $page->view,
                'format'           => $page->format,
                'excerpt'          => $page->excerpt,
                'content'          => $page->content,
                'meta_title'       => $page->meta_title,
                'meta_description' => $page->meta_description,
                'meta_keywords'    => $page->meta_keywords,
                'canonical_url'    => $page->canonical_url,
                'og_image_url'     => $page->og_image_url,
                'noindex'          => $page->noindex,
                'meta_json'        => $page->meta_json ?? [],
                'status'           => $page->status,
                'published_at'     => optional($page->published_at)?->format('Y-m-d\TH:i'),
            ]);

            // Warn if page has visual builder content
            if ($page->builder_html) {
                session()->flash('warning', '⚠️ This page has visual builder content. Editing the "Content" field will replace the visual content with text content. You can safely edit SEO fields, title, and other metadata.');
            }
        } else {
            // Set default values for new page
            $this->title = 'New Page';
            $this->slug = 'new-page';
            $this->content = 'Enter your content here...';
        }
    }

    public function updatedTitle(): void
    {
        $this->slug = Str::slug($this->title);
    }

    public function save(): void
    {
        $data = $this->validate($this->rules());

        // Generate slug from title
        $data['slug'] = Str::slug($this->title);

        // enforce allowed layouts
        if (! in_array($data['layout'], $this->allowedLayouts, true)) {
            $this->addError('layout', 'Invalid layout.');
            return;
        }

        $data['published_at'] = $data['published_at'] ? now()->parse($data['published_at']) : null;

        if ($this->page) {
            $this->page->fill($data);
            $this->page->updated_by_id = auth()->id() ?? null;
            
            // Only clear visual builder content if the content field was actually changed
            if ($this->page->isDirty('content')) {
                $this->page->builder_html = null;
                $this->page->builder_css = null;
                $this->page->builder_json = null;
            }
            
            $this->page->save();
        } else {
            $this->page = Page::create($data + [
                'created_by_id' => auth()->id() ?? null,
                'updated_by_id' => auth()->id() ?? null,
            ]);
        }

        $this->dispatch('saved'); // Livewire 3 dispatch
        session()->flash('status', 'Page saved.');
    }

    protected function rules(): array
    {
        return [
            'title'            => ['required','string','max:255', Rule::unique('pages','title')->ignore($this->page?->id)],
            'layout'           => ['required','string','max:255'],
            'view'             => ['nullable','string','max:255'],
            'format'           => ['required','in:blade,markdown,html'],
            'excerpt'          => ['nullable','string'],
            'content'          => ['required','string'],

            'meta_title'       => ['nullable','string','max:255'],
            'meta_description' => ['nullable','string','max:1000'],
            'meta_keywords'    => ['nullable','string','max:255'],
            'canonical_url'    => ['nullable','url'],
            'og_image_url'     => ['nullable','url'],
            'noindex'          => ['boolean'],
            'meta_json'        => ['nullable','array'],

            'status'           => ['required','in:draft,published'],
            'published_at'     => ['nullable','date'],
        ];
    }

    public function render()
    {
        return view('livewire.admin.page-editor');
    }

    public function openFormatModal()
    {
        $this->showFormatModal = true;
    }

    public function closeFormatModal()
    {
        $this->showFormatModal = false;
    }
}
