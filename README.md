# Laravel Visual Page Builder

A modern Laravel application featuring a powerful visual page builder with drag-and-drop functionality, built with Livewire and GrapesJS.

## 🚀 Features

### Visual Page Builder
- **Drag & Drop Interface**: Intuitive GrapesJS-powered visual editor
- **Real-time Preview**: See changes instantly as you build
- **Image Management**: Drag images directly from your PC into the editor
- **Responsive Design**: Built with Tailwind CSS for mobile-first design
- **Block Library**: Pre-built components including:
  - Hero sections
  - Cards and feature blocks
  - Buttons and forms
  - Grid layouts
  - Text blocks and headings
  - Image galleries

### Content Management
- **Page Management**: Create, edit, and organize pages
- **SEO Optimization**: Built-in meta tags, Open Graph, and canonical URLs
- **Publishing System**: Draft/published status with scheduled publishing
- **User Management**: Role-based access control with Spatie permissions
- **Activity Logging**: Track all changes with Spatie Activity Log

### File Management
- **Image Upload**: Drag & drop image uploads with automatic optimization
- **File Service**: Custom file handling with validation and storage
- **Media Library**: Organized file storage with automatic cleanup

## 🛠 Tech Stack

- **Backend**: Laravel 12.x with PHP 8.2+
- **Frontend**: Livewire 3.x for reactive components
- **Styling**: Tailwind CSS 3.x
- **Visual Editor**: GrapesJS
- **Database**: MySQL/PostgreSQL/SQLite
- **Authentication**: Laravel Breeze
- **Permissions**: Spatie Laravel Permission
- **Activity Logging**: Spatie Laravel Activity Log
- **Media Management**: Spatie Laravel Media Library

## 📦 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd laravel-app
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   php artisan storage:link
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start development server**
   ```bash
   php artisan serve
   ```

## 🎯 Usage

### Creating Pages

1. **Access Admin Panel**: Navigate to `/admin/pages`
2. **Create New Page**: Click "New Page" to create a new page
3. **Visual Builder**: Use the visual builder to design your page
4. **Publish**: Set status to "Published" to make it live

### Visual Builder

1. **Drag Components**: Drag blocks from the left panel into the canvas
2. **Upload Images**: Drag images directly from your PC into the editor
3. **Customize**: Click on elements to edit properties
4. **Save**: Click the save button to persist changes

### Page Structure

- **Admin Routes**: `/admin/*` - Protected admin interface
- **Public Pages**: `/p/{slug}` - Public page display
- **Home Page**: `/` redirects to `/p/home`

## 🏗 Project Structure

```
app/
├── Livewire/
│   ├── Admin/
│   │   ├── CreatePage.php      # Page creation form
│   │   ├── PageEditor.php      # Traditional page editor
│   │   ├── PageList.php        # Pages listing
│   │   └── VisualBuilder.php   # Visual page builder
│   ├── Components/
│   │   └── Modal.php           # Reusable modal component
│   └── ShowPage.php            # Public page display
├── Models/
│   ├── Page.php                # Page model with builder support
│   └── User.php                # User model
└── Services/
    └── FileService.php         # File upload and management

resources/views/
├── layouts/
│   └── admin.blade.php         # Admin layout
└── livewire/
    ├── admin/
    │   ├── visual-builder.blade.php  # Visual builder interface
    │   ├── page-editor.blade.php     # Traditional editor
    │   └── page-list.blade.php       # Pages listing
    └── show-page.blade.php           # Public page display
```

## 🔧 Configuration

### File Storage
The application uses Laravel's file storage system. Configure in `.env`:
```env
FILESYSTEM_DISK=public
```

### Database
Configure your database connection in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_app
DB_USERNAME=root
DB_PASSWORD=
```

## 🎨 Customization

### Adding New Blocks
Add custom blocks to the visual builder in `resources/views/livewire/admin/visual-builder.blade.php`:

```javascript
window.grapesEditor.BlockManager.add('custom-block', {
    label: 'Custom Block',
    content: '<div class="custom-block">Your HTML here</div>'
});
```

### Styling
The application uses Tailwind CSS. Customize styles in:
- `resources/css/app.css`
- `tailwind.config.js`

## 🔒 Security

- **Authentication**: Laravel Breeze with email verification
- **Authorization**: Spatie permissions for role-based access
- **File Upload**: Validated file types and sizes
- **CSRF Protection**: Built-in Laravel CSRF protection
- **SQL Injection**: Eloquent ORM protection

## 📝 Development

### Available Commands
```bash
# Development with hot reload
composer run dev

# Run tests
composer run test

# Code formatting
./vendor/bin/pint

# Database operations
php artisan migrate
php artisan migrate:fresh --seed
```

### Key Features Implementation

- **Visual Builder**: GrapesJS integration with Livewire
- **File Uploads**: Custom FileService with drag & drop
- **Page Management**: Full CRUD with SEO support
- **User Management**: Role-based permissions
- **Activity Logging**: Comprehensive change tracking

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP framework
- [Livewire](https://livewire.laravel.com) - Full-stack framework
- [GrapesJS](https://grapesjs.com) - Visual page builder
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS
- [Spatie](https://spatie.be) - Laravel packages