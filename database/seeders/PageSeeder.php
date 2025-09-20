<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Welcome to Our CMS',
                'layout' => 'layouts.app',
                'format' => 'markdown',
                'content' => "# Hello World\n\nThis is your new CMS page powered by Livewire 3.\n\n## Features\n\n- **Dynamic Layouts**: Choose between different layouts per page\n- **Multiple Formats**: Support for Blade, Markdown, and HTML\n- **SEO Ready**: Built-in meta tags and SEO optimization\n- **Live Preview**: See changes in real-time while editing\n\n## Getting Started\n\n1. Visit `/admin/pages` to create your first page\n2. Choose your layout and format\n3. Add your content and SEO information\n4. Publish and view your page!\n\nHappy content creating! 🎉",
                'excerpt' => 'Welcome to your new Livewire 3 powered CMS. Create, edit, and manage pages with ease.',
                'meta_title' => 'Welcome to Our CMS',
                'meta_description' => 'A powerful CMS built with Laravel and Livewire 3. Create and manage pages with dynamic layouts and SEO optimization.',
                'meta_keywords' => 'cms, laravel, livewire, content management, seo',
                'status' => 'published',
                'published_at' => now(),
            ]
        );

        Page::updateOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'About Us',
                'layout' => 'layouts.app',
                'format' => 'markdown',
                'content' => "# About Our Company\n\nWe are passionate about building amazing web applications using modern technologies.\n\n## Our Mission\n\nTo provide developers with powerful, flexible tools that make content management simple and enjoyable.\n\n## Technology Stack\n\n- **Laravel 12**: The PHP framework for web artisans\n- **Livewire 3**: Full-stack framework for dynamic UIs\n- **Tailwind CSS**: Utility-first CSS framework\n- **Alpine.js**: Lightweight JavaScript framework\n\n## Contact\n\nGet in touch with us to learn more about our services.",
                'excerpt' => 'Learn more about our company and the technologies we use to build amazing web applications.',
                'meta_title' => 'About Us - Our Company',
                'meta_description' => 'Learn about our company, mission, and the modern technologies we use to build web applications.',
                'meta_keywords' => 'about, company, laravel, livewire, web development',
                'status' => 'published',
                'published_at' => now(),
            ]
        );
    }
}
