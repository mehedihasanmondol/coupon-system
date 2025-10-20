# Coupon Image Generator - Implementation Summary

## Overview
A comprehensive coupon image generator system has been implemented that allows you to:
- Create multiple templates with customizable settings
- Generate coupon images with QR codes and coupon numbers
- Select multiple coupons from a table
- Preview, download (as ZIP), and print generated images

## What Was Implemented

### 1. Database & Models
- **Migration**: `2025_10_20_094000_create_coupon_templates_table.php`
- **Model**: `CouponTemplate.php` with all necessary fields and relationships

### 2. Controllers
- **CouponTemplateController.php**: Full CRUD operations for template management
- **CouponController.php**: Extended with image generation methods
  - `showImageGenerator()` - Display the image generator page
  - `generateImages()` - Generate and download images as ZIP
  - `previewImage()` - Preview a single coupon image
  - `printImages()` - Print view for selected coupons
  - `generateCouponImage()` - Core image generation logic

### 3. Views
Created complete UI for:
- **Template Management**:
  - `admin/templates/index.blade.php` - List all templates
  - `admin/templates/create.blade.php` - Create new template
  - `admin/templates/edit.blade.php` - Edit existing template
  
- **Image Generation**:
  - `admin/coupons/image-generator.blade.php` - Main generator interface
  - `admin/coupons/print.blade.php` - Print-optimized view

### 4. Routes
Added comprehensive routes for:
- Template CRUD operations
- Image generation and preview
- Print functionality
- Template activation toggle

### 5. Packages Installed
- **intervention/image** (^3.0) - Image manipulation
- **endroid/qr-code** (^5.0) - QR code generation

### 6. UI Updates
- Updated navigation menu with new links
- Added Bootstrap Icons CDN
- Responsive design with Bengali language support

## Key Features

### Template System
Each template can configure:
- Base template image (JPG/PNG)
- QR code position (X, Y coordinates)
- QR code size (pixels)
- QR code URL pattern with `{coupon_code}` placeholder
- Coupon number position (X, Y coordinates)
- Font size, color (hex), and optional custom font path
- Active/inactive status

### Image Generator
- Select template from visual gallery
- Multi-select coupons from paginated table
- Select all/deselect all functionality
- Preview before generating
- Download all as ZIP file
- Direct print functionality

### QR Code Integration
- Automatically generates QR codes with coupon-specific URLs
- Configurable size and position
- URL pattern supports dynamic coupon code injection

## Technical Implementation

### Image Generation Process
1. Load template image using Intervention Image
2. Generate QR code using Endroid QR Code
3. Place QR code at specified position
4. Add coupon number text with custom styling
5. Return PNG image

### Batch Processing
- Creates temporary directory for batch generation
- Generates individual images for each coupon
- Packages all images into ZIP file
- Cleans up temporary files after download

### Print Functionality
- Opens new window with all generated images
- Print-optimized CSS with page breaks
- Each coupon on separate page

## File Structure

```
app/
├── Http/Controllers/
│   ├── CouponController.php (extended)
│   └── CouponTemplateController.php (new)
├── Models/
│   └── CouponTemplate.php (new)

database/
├── migrations/
│   └── 2025_10_20_094000_create_coupon_templates_table.php (new)
└── seeders/
    └── CouponTemplateSeeder.php (new)

resources/views/
├── admin/
│   ├── templates/
│   │   ├── index.blade.php (new)
│   │   ├── create.blade.php (new)
│   │   └── edit.blade.php (new)
│   └── coupons/
│       ├── image-generator.blade.php (new)
│       └── print.blade.php (new)
└── layouts/
    └── app.blade.php (updated)

routes/
└── web.php (updated)
```

## Next Steps for User

1. **Run storage link**: `php artisan storage:link`
2. **Create directories**:
   - `storage/app/public/templates`
   - `storage/app/public/fonts`
   - `storage/app/temp`
3. **Prepare template image**: High-resolution base image for coupons
4. **Create first template**: Via admin panel
5. **Test with preview**: Adjust positions as needed
6. **Generate images**: Select coupons and download/print

## Configuration Tips

### Finding Positions
1. Open template image in image editor (Photoshop, GIMP, etc.)
2. Hover over desired position
3. Note X and Y coordinates
4. Use these values in template settings

### QR Code URL Pattern
Example: `https://example.com/coupon?code={coupon_code}`
- The `{coupon_code}` placeholder will be replaced with actual coupon number
- Can be any URL structure

### Font Customization
- Place TTF font files in `storage/app/public/fonts/`
- Reference as relative path: `fonts/YourFont.ttf`
- Useful for Bengali or custom fonts

## Security Considerations
- All routes protected by authentication middleware
- File uploads validated for type and size
- Temporary files cleaned up after generation
- Storage directory properly configured

## Performance Notes
- Batch generation may take time for large quantities
- Consider implementing queue jobs for very large batches
- Images are generated on-demand, not pre-generated
- ZIP file creation is memory-efficient

## Browser Compatibility
- Tested with modern browsers (Chrome, Firefox, Edge)
- Print functionality uses standard browser print dialog
- Responsive design works on desktop and tablet

## Troubleshooting
- Check `storage/logs/laravel.log` for errors
- Ensure GD or Imagick PHP extension is installed
- Verify storage permissions (755 for directories, 644 for files)
- Confirm storage link is created properly
