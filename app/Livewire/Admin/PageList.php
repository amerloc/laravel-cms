<?php

namespace App\Livewire\Admin;

use App\Models\Page;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class PageList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $sortBy = 'updated_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sortBy' => ['except' => 'updated_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleStatus($pageId)
    {
        $page = Page::findOrFail($pageId);
        $page->update([
            'status' => $page->status === 'published' ? 'draft' : 'published',
            'updated_by_id' => auth()->id(),
        ]);

        session()->flash('status', "Page '{$page->title}' status updated to {$page->status}.");
    }

    public function deletePage($pageId)
    {
        $page = Page::findOrFail($pageId);
        $page->delete();
        session()->flash('status', "Page '{$page->title}' deleted successfully.");
    }

    public function render()
    {
        $pages = Page::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%')
                      ->orWhere('excerpt', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.page-list', [
            'pages' => $pages,
        ]);
    }
}
