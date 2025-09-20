<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Modal extends Component
{
    public bool $show = false;
    public string $title = '';
    public string $size = 'md'; // sm, md, lg, xl, 2xl
    public bool $closable = true;
    public bool $closeOnBackdrop = true;

    protected $listeners = [
        'openModal' => 'open',
        'closeModal' => 'close',
    ];

    public function mount($show = false, $title = '', $size = 'md')
    {
        $this->show = $show;
        $this->title = $title;
        $this->size = $size;
    }

    public function open($title = '', $size = 'md')
    {
        $this->title = $title;
        $this->size = $size;
        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
        $this->dispatch('modal-closed');
    }

    public function getSizeClass()
    {
        return match($this->size) {
            'sm' => 'md:w-1/3',
            'md' => 'md:w-1/2',
            'lg' => 'md:w-2/3',
            'xl' => 'md:w-3/4',
            '2xl' => 'md:w-5/6',
            default => 'md:w-1/2',
        };
    }

    public function render()
    {
        return view('livewire.components.modal');
    }
}
