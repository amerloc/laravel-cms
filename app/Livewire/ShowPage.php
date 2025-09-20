<?php

namespace App\Livewire;

use App\Models\Page;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class ShowPage extends Component
{
    public string $slug = '';
    public Page $page;

    public function mount(?string $slug = null): void
    {
        // Support home page with empty slug
        $this->slug = $slug ?? 'home';

        $this->page = Page::published()->where('slug', $this->slug)->firstOrFail();
    }

    protected function renderContent(): HtmlString
    {
        // If page built with builder, prefer compiled HTML
        if ($this->page->builder_html) {
            $html = $this->page->builder_html;
            // Optionally inline CSS at top or push to layout
            $css = $this->page->builder_css;
            view()->share('builder_inline_css', $css); // layout can print it
            return new HtmlString($html);
        }

        // fallback to your blade/markdown/html modes...
        $content = $this->page->content;

        return match ($this->page->format) {
            'blade'    => new HtmlString(Blade::render($content, ['page' => $this->page])),
            'markdown' => new HtmlString(app('markdown')->convert($content)->getContent()),
            'html'     => new HtmlString($content),
            default    => new HtmlString(e($content)),
        };
    }

    protected function layoutName(): string
    {
        // Safety: whitelist only layouts inside resources/views/layouts/*
        $allowed = ['layouts.app','layouts.admin'];
        return in_array($this->page->layout, $allowed, true) ? $this->page->layout : 'layouts.app';
    }

    public function render()
    {
        $html = $this->renderContent();

        // Pass SEO/meta to the layout via layout data
        $layoutData = [
            'meta' => [
                'title'       => $this->page->meta_title ?: $this->page->title,
                'description' => $this->page->meta_description,
                'keywords'    => $this->page->meta_keywords,
                'canonical'   => $this->page->canonical_url,
                'og_image'    => $this->page->og_image_url,
                'noindex'     => $this->page->noindex,
                'extra'       => $this->page->meta_json ?? [],
            ],
            'page' => $this->page,
        ];

        // If a specific Blade "view" is provided, use it (it can itself print $content).
        if ($this->page->view) {
            return view($this->page->view, [
                    'page'    => $this->page,
                    'content' => $html,
                ])
                ->layout($this->layoutName(), $layoutData);
        }

        // Default generic page view
        return view('pages.show', [
                'page'    => $this->page,
                'content' => $html,
            ])
            ->layout($this->layoutName(), $layoutData);
    }
}
