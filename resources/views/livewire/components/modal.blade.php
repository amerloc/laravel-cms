@if($show)
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" 
     @if($closeOnBackdrop) wire:click="close" @endif>
    <div class="relative top-20 mx-auto p-5 border w-11/12 {{ $this->getSizeClass() }} shadow-lg rounded-md bg-white"
         wire:click.stop>
        <div class="mt-3">
            @if($title)
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-900">{{ $title }}</h3>
                    @if($closable)
                        <button wire:click="close" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    @endif
                </div>
            @endif
            
            <div class="modal-content">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
@endif

<script>
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && @this.show) {
        @this.close();
    }
});
</script>