<?php

namespace App\Livewire\Admin;

use App\Models\Page;
use App\Services\FileService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class VisualBuilder extends Component
{
    use WithFileUploads;

    public Page $page;

    // persisted on save
    public ?array $builder_json = null;
    public ?string $builder_html = null;
    public ?string $builder_css = null;

    // Image upload
    public $uploadedImage;
    public $availableImages = [];

    public function mount(Page $page): void
    {
        $this->page = $page;
        $this->builder_json = $page->builder_json ?? null;
        $this->builder_html = $page->builder_html ?? null;
        $this->builder_css = $page->builder_css ?? null;
        
        // Load available images
        $this->loadAvailableImages();
    }

    public function saveFromEditor(): void
    {
        try {
            \Log::info('Save method called');
            
            // Dispatch event to JavaScript to get current editor data
            $this->dispatch('get-editor-data');
        } catch (\Exception $e) {
            \Log::error('Error in saveFromEditor:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error saving content: ' . $e->getMessage());
        }
    }

    #[On('save-editor-data')]
    public function saveEditorData($json = null, $html = '', $css = ''): void
    {
        try {
            \Log::info('Saving editor data:', [
                'json' => $json,
                'html' => $html,
                'css' => $css
            ]);
            
            $this->builder_json = $json;
            $this->builder_html = $html;
            $this->builder_css = $css;

            \Log::info('About to save page:', [
                'page_id' => $this->page->id,
                'builder_json' => $this->builder_json,
                'builder_html' => $this->builder_html,
                'builder_css' => $this->builder_css,
            ]);

            $this->page->forceFill([
                'builder_json' => $this->builder_json,
                'builder_html' => $this->builder_html,
                'builder_css' => $this->builder_css,
                'format' => 'html', // ensure renderer treats as HTML
            ])->save();

            \Log::info('Page saved successfully');
            
            // Don't dispatch any events to prevent re-render
            // Just set a success message that won't cause re-render
            $this->js('
                setTimeout(() => {
                    const saveBtn = document.getElementById("saveBtn");
                    if (saveBtn) {
                        saveBtn.textContent = "Saved!";
                        saveBtn.style.backgroundColor = "#10b981";
                        setTimeout(() => {
                            saveBtn.textContent = "Save";
                            saveBtn.style.backgroundColor = "#4f46e5";
                        }, 2000);
                    }
                }, 100);
            ');
        } catch (\Exception $e) {
            \Log::error('Error saving builder content:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error saving content: ' . $e->getMessage());
        }
    }

    public function uploadImage($file = null): array
    {
        try {
            // Handle both Livewire file uploads and direct file uploads
            $uploadedFile = $file ?: $this->uploadedImage;
            
            if (!$uploadedFile) {
                throw new \Exception('No file provided');
            }

            $fileService = new FileService();
            $result = $fileService->uploadBuilderImage($uploadedFile, 'builder');

            // Reset the uploaded image if it was from the form
            if (!$file) {
                $this->uploadedImage = null;
            }

            // Show success message
            session()->flash('status', 'Image uploaded successfully!');
            
            return $result;

        } catch (\Exception $e) {
            \Log::error('Image upload failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Failed to upload image: ' . $e->getMessage());
            throw $e;
        }
    }

    public function loadAvailableImages(): void
    {
        try {
            $fileService = new FileService();
            $this->availableImages = $fileService->getImages('images/builder');
        } catch (\Exception $e) {
            \Log::error('Failed to load available images:', [
                'error' => $e->getMessage()
            ]);
            $this->availableImages = [];
        }
    }

    public function deleteImage(string $path): void
    {
        try {
            $fileService = new FileService();
            $deleted = $fileService->delete($path);

            if ($deleted) {
                $this->loadAvailableImages();
                session()->flash('status', 'Image deleted successfully!');
            } else {
                session()->flash('error', 'Failed to delete image.');
            }
        } catch (\Exception $e) {
            \Log::error('Image deletion failed:', [
                'error' => $e->getMessage(),
                'path' => $path
            ]);
            session()->flash('error', 'Failed to delete image: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.visual-builder');
    }
}
