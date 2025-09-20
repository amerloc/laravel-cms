<div class="space-y-4">
    @if (session('status'))
        <div class="rounded bg-green-100 p-2 text-green-900">{{ session('status') }}</div>
    @endif
    
    @if (session('error'))
        <div class="rounded bg-red-100 p-2 text-red-900">{{ session('error') }}</div>
    @endif

            <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">{{ $page->title }} — Visual Builder</h1>
        <div class="space-x-3">
            <a href="{{ route('page.show', ['slug' => $page->slug]) }}" target="_blank" class="underline">View</a>
            <button id="saveBtn" class="px-3 py-1 rounded bg-indigo-600 text-white">Save</button>
        </div>
    </div>


    <!-- GrapesJS Editor -->
    <div id="gjs" class="border rounded" style="height:75vh;" wire:ignore></div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/grapesjs/dist/css/grapes.min.css">
@endpush

@push('scripts')
<script src="https://unpkg.com/grapesjs"></script>
<script>
console.log('GrapesJS script loaded');

// Initialize GrapesJS only once
let initialized = false;

        document.addEventListener('livewire:navigated', function() {
            console.log('Livewire navigated event fired');
            if (!initialized) {
            initGrapesJS();
            initialized = true;
}
});

// Prevent editor from being destroyed on Livewire updates
document.addEventListener('livewire:update', function() {
    console.log('Livewire update event fired - preserving editor');
    // Don't reinitialize if editor already exists
});


document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded event fired');
    if (!initialized) {
        initGrapesJS();
        initialized = true;
    }
});

function initGrapesJS() {
    console.log('initGrapesJS called');
    
    // Wait a bit for Livewire to finish rendering
    setTimeout(function() {
        console.log('Looking for GrapesJS container...');
    const container = document.getElementById('gjs');
    if (!container) {
        console.error('GrapesJS container not found');
        return;
    }
        console.log('GrapesJS container found:', container);

    // Destroy existing editor if it exists
    if (window.grapesEditor) {
        console.log('Destroying existing editor');
            window.grapesEditor.destroy();
        }

    console.log('Initializing GrapesJS...');
        try {
            window.grapesEditor = grapesjs.init({
                container: '#gjs',
                height: '75vh',
                fromElement: false,
                storageManager: { type: null }, // we handle save with Livewire
                plugins: [
                  // you can add grapesjs-preset-webpage, navbar, blocks, etc.
                ],
                canvas: {
                    styles: [
                        'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css'
                    ]
                },
                assetManager: {
                    upload: false, // Disable default upload
                    uploadText: 'Drop files here or click to upload',
                    addBtnText: 'Add image'
                }
            });
            console.log('GrapesJS initialized successfully');

            // Load prior state (html/css) if exists
            @if($builder_html || $builder_css)
                console.log('Loading existing builder content');
                window.grapesEditor.setComponents(@json($builder_html ?? ''));
                window.grapesEditor.setStyle(@json($builder_css ?? ''));
            @elseif($builder_json)
                console.log('Loading existing builder JSON');
                window.grapesEditor.loadProjectData(@json($builder_json));
            @else
                console.log('No existing builder content, starting fresh');
                // Add some default content to test
                window.grapesEditor.setComponents('<div class="container mx-auto py-8"><h1>Welcome to the Visual Builder</h1><p>Drag blocks from the left panel to start building your page.</p></div>');
            @endif

            // Add comprehensive block library
            console.log('Adding blocks...');
    
    // Layout Blocks
    window.grapesEditor.BlockManager.add('section', {
        label: 'Section', 
        content: '<section class="container mx-auto py-12"><h2>Section Title</h2><p>Section content goes here...</p></section>'
    });
    
    window.grapesEditor.BlockManager.add('container', {
        label: 'Container', 
        content: '<div class="container mx-auto px-4"><p>Container content</p></div>'
    });

    // Hero Blocks
    window.grapesEditor.BlockManager.add('hero', {
        label: 'Hero', 
        content: '<section class="min-h-[50vh] flex items-center justify-center bg-gray-100"><div class="text-center"><h1 class="text-4xl font-bold mb-4">Hero Title</h1><p class="text-lg text-gray-600">Hero subtitle</p></div></section>'
    });

    window.grapesEditor.BlockManager.add('hero-dark', {
        label: 'Hero (Dark)', 
        content: '<section class="min-h-[50vh] flex items-center justify-center bg-gray-900 text-white"><div class="text-center"><h1 class="text-4xl font-bold mb-4">Hero Title</h1><p class="text-lg text-gray-300">Hero subtitle</p></div></section>'
    });

    // Card Blocks
    window.grapesEditor.BlockManager.add('card', {
        label: 'Card', 
        content: '<div class="bg-white rounded-lg shadow-md p-6"><h3 class="text-xl font-semibold mb-2">Card Title</h3><p class="text-gray-600">Card content goes here...</p></div>'
    });

    window.grapesEditor.BlockManager.add('card-feature', {
        label: 'Feature Card', 
        content: '<div class="bg-white rounded-lg shadow-lg p-8 text-center"><div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></div><h3 class="text-xl font-semibold mb-2">Feature Title</h3><p class="text-gray-600">Feature description goes here.</p></div>'
    });

    // Button Blocks
    window.grapesEditor.BlockManager.add('button', {
        label: 'Button', 
        content: '<button class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">Click Me</button>'
    });

    window.grapesEditor.BlockManager.add('button-outline', {
        label: 'Button (Outline)', 
        content: '<button class="border border-indigo-600 text-indigo-600 px-6 py-2 rounded-md hover:bg-indigo-600 hover:text-white">Click Me</button>'
    });

    window.grapesEditor.BlockManager.add('button-large', {
        label: 'Button (Large)', 
        content: '<button class="bg-indigo-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-indigo-700">Get Started</button>'
    });

    // Text Blocks
    window.grapesEditor.BlockManager.add('text', {
        label: 'Text Block', 
        content: '<div class="prose max-w-none"><h2>Heading</h2><p>This is a paragraph with some text content.</p></div>'
    });

    window.grapesEditor.BlockManager.add('heading', {
        label: 'Heading', 
        content: '<h1 class="text-4xl font-bold text-gray-900 mb-4">Main Heading</h1>'
    });

    window.grapesEditor.BlockManager.add('subheading', {
        label: 'Subheading', 
        content: '<h2 class="text-2xl font-semibold text-gray-800 mb-3">Subheading</h2>'
    });

    // Image Blocks - using data URI placeholders to avoid network issues
    window.grapesEditor.BlockManager.add('image', {
        label: 'Image', 
        content: '<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAwIiBoZWlnaHQ9IjQwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjNGY0NmU1Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIyNCIgZmlsbD0iI2ZmZmZmZiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkNsaWNrIHRvIHNlbGVjdCBpbWFnZTwvdGV4dD48L3N2Zz4=" alt="Select an image" class="w-full h-auto rounded-lg cursor-pointer" data-gjs-type="image">'
    });

    window.grapesEditor.BlockManager.add('image-circle', {
        label: 'Image (Circle)', 
        content: '<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48Y2lyY2xlIGN4PSIxMDAiIGN5PSIxMDAiIHI9IjEwMCIgZmlsbD0iIzRmNDZlNSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMTQiIGZpbGw9IiNmZmZmZmYiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5TZWxlY3QgaW1hZ2U8L3RleHQ+PC9zdmc+" alt="Select an image" class="w-32 h-32 rounded-full mx-auto cursor-pointer" data-gjs-type="image">'
    });

    window.grapesEditor.BlockManager.add('image-hero', {
        label: 'Hero Image', 
        content: '<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwMCIgaGVpZ2h0PSI2MDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iIzRmNDZlNSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMzIiIGZpbGw9IiNmZmZmZmYiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5IZXJvIEltYWdlPC90ZXh0Pjwvc3ZnPg==" alt="Select hero image" class="w-full h-64 object-cover rounded-lg cursor-pointer" data-gjs-type="image">'
    });

    // List Blocks
    window.grapesEditor.BlockManager.add('list', {
        label: 'List', 
        content: '<ul class="list-disc list-inside space-y-2"><li>First item</li><li>Second item</li><li>Third item</li></ul>'
    });

    window.grapesEditor.BlockManager.add('list-check', {
        label: 'Checklist', 
        content: '<ul class="space-y-2"><li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>First item</li><li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>Second item</li></ul>'
    });

    // Grid Blocks
    window.grapesEditor.BlockManager.add('grid-2', {
        label: 'Grid (2 columns)', 
        content: '<div class="grid grid-cols-1 md:grid-cols-2 gap-6"><div class="bg-gray-100 p-4 rounded"><h3>Column 1</h3><p>Content for first column</p></div><div class="bg-gray-100 p-4 rounded"><h3>Column 2</h3><p>Content for second column</p></div></div>'
    });

    window.grapesEditor.BlockManager.add('grid-3', {
        label: 'Grid (3 columns)', 
        content: '<div class="grid grid-cols-1 md:grid-cols-3 gap-6"><div class="bg-gray-100 p-4 rounded"><h3>Column 1</h3><p>Content</p></div><div class="bg-gray-100 p-4 rounded"><h3>Column 2</h3><p>Content</p></div><div class="bg-gray-100 p-4 rounded"><h3>Column 3</h3><p>Content</p></div></div>'
    });

    // Form Blocks
    window.grapesEditor.BlockManager.add('form', {
        label: 'Contact Form', 
        content: '<form class="max-w-md mx-auto"><div class="mb-4"><label class="block text-sm font-medium mb-2">Name</label><input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Your name"></div><div class="mb-4"><label class="block text-sm font-medium mb-2">Email</label><input type="email" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="your@email.com"></div><div class="mb-4"><label class="block text-sm font-medium mb-2">Message</label><textarea class="w-full border border-gray-300 rounded-md px-3 py-2" rows="4" placeholder="Your message"></textarea></div><button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700">Send Message</button></form>'
    });

    // Quote Blocks
    window.grapesEditor.BlockManager.add('quote', {
        label: 'Quote', 
        content: '<blockquote class="border-l-4 border-indigo-500 pl-4 italic text-lg text-gray-700">"This is an inspiring quote that will motivate your readers."</blockquote>'
    });

    // Divider Blocks
    window.grapesEditor.BlockManager.add('divider', {
        label: 'Divider', 
        content: '<hr class="my-8 border-gray-300">'
    });

    window.grapesEditor.BlockManager.add('spacer', {
        label: 'Spacer', 
        content: '<div class="py-8"></div>'
    });

            const saveBtn = document.getElementById('saveBtn');
            if (saveBtn) {
                console.log('Setting up save button');
                
                // Remove any existing event listeners
                saveBtn.replaceWith(saveBtn.cloneNode(true));
                const newSaveBtn = document.getElementById('saveBtn');
                
                newSaveBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    if (newSaveBtn.disabled) {
                        console.log('Save already in progress, ignoring click');
                        return;
                    }
                    
                    console.log('Save button clicked');
                    try {
                        // Get data from GrapesJS
                        const html = window.grapesEditor.getHtml();
                        const css = window.grapesEditor.getCss();
                        const json = window.grapesEditor.getProjectData();

                        console.log('Sending editor data:', { html, css, json });

                        // Show feedback
                        newSaveBtn.textContent = 'Saving...';
                        newSaveBtn.disabled = true;

                        // Call Livewire method directly with data
                        @this.call('saveEditorData', json, html, css)
                            .then(() => {
                                console.log('Save successful!');
                                // Feedback is handled by PHP via $this->js()
                            })
                            .catch((error) => {
                                console.error('Save failed:', error);
                                newSaveBtn.textContent = 'Error';
                                newSaveBtn.style.backgroundColor = '#ef4444';
                                setTimeout(() => {
                                    newSaveBtn.textContent = 'Save';
                                    newSaveBtn.style.backgroundColor = '#4f46e5';
                                    newSaveBtn.disabled = false;
                                }, 2000);
                            });
                        
                    } catch (error) {
                        console.error('Error saving:', error);
                        alert('Error saving content: ' + error.message);
                        newSaveBtn.textContent = 'Save';
                        newSaveBtn.disabled = false;
                    }
                });
            } else {
                console.error('Save button not found');
            }

            // Handle drag and drop of files
            const canvas = window.grapesEditor.Canvas.getFrameEl();
            if (canvas) {
                canvas.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'copy';
                });

                canvas.addEventListener('drop', function(e) {
                    e.preventDefault();
                    const files = Array.from(e.dataTransfer.files);
                    const imageFiles = files.filter(file => file.type.startsWith('image/'));
                    
                    if (imageFiles.length > 0) {
                        handleFileDrop(imageFiles);
                    }
                });
            }

            // Add image click handler for existing images
            window.grapesEditor.on('component:selected', function(component) {
                if (component.get('type') === 'image') {
                    component.on('change:src', function() {
                        console.log('Image src changed:', component.get('src'));
                    });
                }
            });

            console.log('GrapesJS setup complete');
        } catch (error) {
            console.error('Error initializing GrapesJS:', error);
        }
    }, 500); // Increased timeout
}

// Handle file drop in GrapesJS canvas
async function handleFileDrop(files) {
    console.log('Handling file drop:', files);
    
    for (const file of files) {
        try {
            console.log('Uploading file:', file.name, file.size);
            
            // Upload via Livewire
            const result = await @this.call('uploadImage', file);
            
            if (result && result.url) {
                console.log('Upload successful:', result.url);
                
                // Add the uploaded image to the canvas
                const imageComponent = window.grapesEditor.addComponent({
                    type: 'image',
                    src: result.url,
                    alt: file.name,
                    class: 'w-full h-auto rounded-lg'
                });
                
                console.log('Image component added to canvas');
            } else {
                console.error('Upload failed - no URL returned');
            }
            
        } catch (error) {
            console.error('Upload error:', error);
        }
    }
}
</script>
@endpush
