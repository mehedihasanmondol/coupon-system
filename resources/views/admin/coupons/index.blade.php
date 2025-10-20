@extends('layouts.app')

@section('title', 'কুপন ব্যবস্থাপনা')
<style>
    .pagination-links svg {
    width: 20px;
}
</style>
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>কুপন ব্যবস্থাপনা</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateModal">
        কুপন জেনারেট করুন
    </button>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6>মোট কুপন</h6>
                <h3>{{ $totalCoupons }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6>খোলা কুপন</h6>
                <h3>{{ $openedCoupons }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6>ড্র হওয়া কুপন</h6>
                <h3>{{ $drawnCoupons }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <button type="button" class="btn btn-sm btn-info" id="select-all-btn">সব নির্বাচন</button>
            <button type="button" class="btn btn-sm btn-warning" id="deselect-all-btn">সব বাতিল</button>
            <span class="ms-3" id="selected-count">নির্বাচিত: <strong>0</strong></span>
        </div>
        <div>
            <button type="button" class="btn btn-sm btn-success" id="generate-images-btn" disabled>
                <i class="bi bi-image"></i> ইমেজ জেনারেট করুন
            </button>
        </div>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th width="50">
                        <input type="checkbox" id="select-all-checkbox">
                    </th>
                    <th>কুপন নম্বর</th>
                    <th>সিক্রেট কোড</th>
                    <th>স্ট্যাটাস</th>
                    <th>খোলার সময়</th>
                    <th>তৈরির তারিখ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr>
                    <td>
                        <input type="checkbox" class="coupon-checkbox" value="{{ $coupon->id }}">
                    </td>
                    <td><strong>{{ $coupon->coupon_number }}</strong></td>
                    <td><code>{{ $coupon->secret_code }}</code></td>
                    <td>
                        @if($coupon->is_drawn)
                            <span class="badge bg-warning">ড্র হয়েছে</span>
                        @elseif($coupon->is_opened)
                            <span class="badge bg-success">খোলা</span>
                        @else
                            <span class="badge bg-secondary">বন্ধ</span>
                        @endif
                    </td>
                    <td>{{ $coupon->opened_at ? $coupon->opened_at->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $coupon->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">কোনো কুপন নেই</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-3 pagination-links">
            {{ $coupons->links() }}
        </div>
    </div>
</div>

<!-- Generate Modal -->
<div class="modal fade" id="generateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">কুপন জেনারেট করুন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.coupons.generate') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">কতটি কুপন জেনারেট করতে চান?</label>
                        <input type="number" name="quantity" class="form-control" min="1" max="1000" required>
                        <small class="text-muted">সর্বোচ্চ ১০০০ টি কুপন একসাথে জেনারেট করতে পারবেন</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary">জেনারেট করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Template Selection Modal -->
<div class="modal fade" id="templateModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">টেমপ্লেট নির্বাচন করুন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row" id="templates-container">
                    <div class="col-12 text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">লোড হচ্ছে...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const couponCheckboxes = document.querySelectorAll('.coupon-checkbox');
    const selectAllBtn = document.getElementById('select-all-btn');
    const deselectAllBtn = document.getElementById('deselect-all-btn');
    const generateImagesBtn = document.getElementById('generate-images-btn');
    const selectedCountEl = document.getElementById('selected-count');
    
    let templates = [];
    
    // Load templates
    fetch('{{ route("admin.templates.index") }}')
        .then(response => response.text())
        .then(html => {
            // Parse templates from the response (you might want to create an API endpoint instead)
            // For now, we'll load them when modal opens
        });
    
    function updateSelectedCount() {
        const selected = document.querySelectorAll('.coupon-checkbox:checked').length;
        selectedCountEl.innerHTML = `নির্বাচিত: <strong>${selected}</strong>`;
        generateImagesBtn.disabled = selected === 0;
    }
    
    selectAllCheckbox.addEventListener('change', function() {
        couponCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });
    
    selectAllBtn.addEventListener('click', function() {
        couponCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        selectAllCheckbox.checked = true;
        updateSelectedCount();
    });
    
    deselectAllBtn.addEventListener('click', function() {
        couponCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        selectAllCheckbox.checked = false;
        updateSelectedCount();
    });
    
    couponCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
    
    generateImagesBtn.addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.coupon-checkbox:checked'))
            .map(cb => cb.value);
        
        if (selectedIds.length === 0) {
            alert('অন্তত একটি কুপন নির্বাচন করুন');
            return;
        }
        
        // Load templates and show modal
        loadTemplates(selectedIds);
    });
    
    function loadTemplates(selectedIds) {
        const container = document.getElementById('templates-container');
        container.innerHTML = '<div class="col-12 text-center"><div class="spinner-border" role="status"></div></div>';
        
        // Fetch templates via API
        fetch('/admin/templates-api')
            .then(response => response.json())
            .then(templates => {
                if (templates.length === 0) {
                    container.innerHTML = '<div class="col-12"><div class="alert alert-warning">কোন সক্রিয় টেমপ্লেট পাওয়া যায়নি। <a href="' + '{{ route("admin.templates.create") }}' + '">নতুন টেমপ্লেট তৈরি করুন</a></div></div>';
                    return;
                }
                
                container.innerHTML = '';
                templates.forEach(template => {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 mb-3';
                    col.innerHTML = `
                        <div class="card template-card" style="cursor: pointer;">
                            <img src="${template.image_url}" class="card-img-top" alt="${template.name}">
                            <div class="card-body">
                                <h6 class="card-title">${template.name}</h6>
                                <button type="button" class="btn btn-sm btn-primary w-100 select-template-btn" 
                                        data-template-id="${template.id}">
                                    নির্বাচন করুন
                                </button>
                            </div>
                        </div>
                    `;
                    container.appendChild(col);
                });
                
                // Add click handlers
                document.querySelectorAll('.select-template-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const templateId = this.dataset.templateId;
                        generateWithTemplate(selectedIds, templateId);
                    });
                });
                
                // Show modal
                new bootstrap.Modal(document.getElementById('templateModal')).show();
            })
            .catch(error => {
                console.error('Error loading templates:', error);
                container.innerHTML = '<div class="col-12"><div class="alert alert-danger">টেমপ্লেট লোড করতে সমস্যা হয়েছে</div></div>';
            });
    }
    
    function generateWithTemplate(couponIds, templateId) {
        // Show action selection modal
        showActionModal(couponIds, templateId);
    }
    
    function showActionModal(couponIds, templateId) {
        // Close template modal
        const templateModalEl = document.getElementById('templateModal');
        const templateModalInstance = bootstrap.Modal.getInstance(templateModalEl);
        if (templateModalInstance) {
            templateModalInstance.hide();
        }
        
        // Remove existing action modal if any
        const existingModal = document.getElementById('actionModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Create modal HTML
        const modalDiv = document.createElement('div');
        modalDiv.className = 'modal fade';
        modalDiv.id = 'actionModal';
        modalDiv.setAttribute('tabindex', '-1');
        modalDiv.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">অ্যাকশন নির্বাচন করুন</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p class="mb-4">আপনি <strong>${couponIds.length}</strong> টি কুপনের ইমেজ জেনারেট করতে চলেছেন।</p>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary btn-lg" id="download-btn">
                                <i class="bi bi-download"></i> ডাউনলোড করুন (ZIP)
                            </button>
                            <button type="button" class="btn btn-info btn-lg" id="print-btn">
                                <i class="bi bi-printer"></i> প্রিন্ট করুন
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল করুন</button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modalDiv);
        
        // Add event listeners
        document.getElementById('download-btn').addEventListener('click', function() {
            downloadImages(couponIds, templateId);
        });
        
        document.getElementById('print-btn').addEventListener('click', function() {
            printImages(couponIds, templateId);
        });
        
        // Show modal
        new bootstrap.Modal(modalDiv).show();
    }
    
    window.downloadImages = function(couponIds, templateId) {
        const modal = bootstrap.Modal.getInstance(document.getElementById('actionModal'));
        if (modal) modal.hide();
        
        // Show loading message
        const loadingToast = document.createElement('div');
        loadingToast.className = 'position-fixed top-0 end-0 p-3';
        loadingToast.style.zIndex = '9999';
        loadingToast.innerHTML = `
            <div class="toast show" role="alert">
                <div class="toast-header">
                    <strong class="me-auto">ডাউনলোড হচ্ছে...</strong>
                </div>
                <div class="toast-body">
                    অনুগ্রহ করে অপেক্ষা করুন
                </div>
            </div>
        `;
        document.body.appendChild(loadingToast);
        
        submitForm(couponIds, templateId, '{{ route("admin.coupons.generate-images") }}', '_self');
        
        // Remove loading message after 2 seconds
        setTimeout(() => {
            loadingToast.remove();
        }, 2000);
    };
    
    window.printImages = function(couponIds, templateId) {
        const modal = bootstrap.Modal.getInstance(document.getElementById('actionModal'));
        if (modal) modal.hide();
        
        submitForm(couponIds, templateId, '{{ route("admin.coupons.print-images") }}', '_blank');
    };
    
    function submitForm(couponIds, templateId, action, target) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = action;
        if (target) {
            form.target = target;
        }
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        // Add template ID
        const templateInput = document.createElement('input');
        templateInput.type = 'hidden';
        templateInput.name = 'template_id';
        templateInput.value = templateId;
        form.appendChild(templateInput);
        
        // Add coupon IDs
        couponIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'coupon_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
        
        // Clean up form after a short delay
        setTimeout(() => {
            if (document.body.contains(form)) {
                document.body.removeChild(form);
            }
        }, 100);
    }
});
</script>
@endsection