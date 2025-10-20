@extends('layouts.app')

@section('title', 'কুপন ইমেজ জেনারেটর')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>কুপন ইমেজ জেনারেটর</h2>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> ফিরে যান
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card mb-4">
    <div class="card-header">
        <h5>টেমপ্লেট নির্বাচন করুন</h5>
    </div>
    <div class="card-body">
        @if($templates->count() > 0)
            <div class="row">
                @foreach($templates as $template)
                    <div class="col-md-3 mb-3">
                        <div class="card template-card" data-template-id="{{ $template->id }}">
                            <img src="{{ $template->template_image_url }}" class="card-img-top" alt="{{ $template->name }}">
                            <div class="card-body">
                                <h6 class="card-title">{{ $template->name }}</h6>
                                <button type="button" class="btn btn-sm btn-primary select-template" data-template-id="{{ $template->id }}">
                                    নির্বাচন করুন
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning">
                কোন সক্রিয় টেমপ্লেট পাওয়া যায়নি। <a href="{{ route('admin.templates.create') }}">নতুন টেমপ্লেট তৈরি করুন</a>
            </div>
        @endif
    </div>
</div>

<div class="card" id="coupon-selection-card" style="display: none;">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>কুপন নির্বাচন করুন</h5>
        <div>
            <button type="button" class="btn btn-sm btn-info" id="select-all-btn">সব নির্বাচন করুন</button>
            <button type="button" class="btn btn-sm btn-warning" id="deselect-all-btn">সব বাতিল করুন</button>
        </div>
    </div>
    <div class="card-body">
        <form id="generate-form" method="POST">
            @csrf
            <input type="hidden" name="template_id" id="selected-template-id">
            
            <div class="mb-3">
                <button type="button" class="btn btn-success" id="preview-btn">
                    <i class="bi bi-eye"></i> প্রিভিউ দেখুন
                </button>
                <button type="submit" class="btn btn-primary" formaction="{{ route('admin.coupons.generate-images') }}" formtarget="_blank">
                    <i class="bi bi-download"></i> ডাউনলোড করুন (ZIP)
                </button>
                <button type="submit" class="btn btn-info" formaction="{{ route('admin.coupons.print-images') }}" formtarget="_blank">
                    <i class="bi bi-printer"></i> প্রিন্ট করুন
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="select-all-checkbox">
                            </th>
                            <th>কুপন নম্বর</th>
                            <th>সিক্রেট কোড</th>
                            <th>স্ট্যাটাস</th>
                            <th>তৈরির তারিখ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coupons as $coupon)
                        <tr>
                            <td>
                                <input type="checkbox" name="coupon_ids[]" value="{{ $coupon->id }}" class="coupon-checkbox">
                            </td>
                            <td>{{ $coupon->coupon_number }}</td>
                            <td>{{ $coupon->secret_code }}</td>
                            <td>
                                @if($coupon->is_opened)
                                    <span class="badge bg-success">খোলা</span>
                                @else
                                    <span class="badge bg-secondary">বন্ধ</span>
                                @endif
                            </td>
                            <td>{{ $coupon->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $coupons->links() }}
            </div>
        </form>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ইমেজ প্রিভিউ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="preview-image" src="" alt="Preview" style="max-width: 100%;">
            </div>
        </div>
    </div>
</div>

<style>
.template-card {
    cursor: pointer;
    transition: all 0.3s;
}

.template-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.template-card.selected {
    border: 3px solid #0d6efd;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const templates = document.querySelectorAll('.select-template');
    const couponSelectionCard = document.getElementById('coupon-selection-card');
    const selectedTemplateInput = document.getElementById('selected-template-id');
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const couponCheckboxes = document.querySelectorAll('.coupon-checkbox');
    const selectAllBtn = document.getElementById('select-all-btn');
    const deselectAllBtn = document.getElementById('deselect-all-btn');
    const previewBtn = document.getElementById('preview-btn');

    templates.forEach(btn => {
        btn.addEventListener('click', function() {
            const templateId = this.dataset.templateId;
            selectedTemplateInput.value = templateId;
            
            // Remove selected class from all cards
            document.querySelectorAll('.template-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selected class to clicked card
            this.closest('.template-card').classList.add('selected');
            
            // Show coupon selection
            couponSelectionCard.style.display = 'block';
            couponSelectionCard.scrollIntoView({ behavior: 'smooth' });
        });
    });

    selectAllCheckbox.addEventListener('change', function() {
        couponCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    selectAllBtn.addEventListener('click', function() {
        couponCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        selectAllCheckbox.checked = true;
    });

    deselectAllBtn.addEventListener('click', function() {
        couponCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        selectAllCheckbox.checked = false;
    });

    previewBtn.addEventListener('click', function() {
        const selectedCoupon = document.querySelector('.coupon-checkbox:checked');
        const templateId = selectedTemplateInput.value;

        if (!selectedCoupon) {
            alert('অন্তত একটি কুপন নির্বাচন করুন');
            return;
        }

        if (!templateId) {
            alert('একটি টেমপ্লেট নির্বাচন করুন');
            return;
        }

        const previewUrl = '{{ route("admin.coupons.preview-image") }}?coupon_id=' + selectedCoupon.value + '&template_id=' + templateId;
        document.getElementById('preview-image').src = previewUrl;
        
        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        modal.show();
    });

    // Form validation for download and print buttons
    const generateForm = document.getElementById('generate-form');
    generateForm.addEventListener('submit', function(e) {
        const selectedCoupons = document.querySelectorAll('.coupon-checkbox:checked');
        const templateId = selectedTemplateInput.value;

        if (!templateId) {
            e.preventDefault();
            alert('একটি টেমপ্লেট নির্বাচন করুন');
            return false;
        }

        if (selectedCoupons.length === 0) {
            e.preventDefault();
            alert('অন্তত একটি কুপন নির্বাচন করুন');
            return false;
        }

        // Show loading message
        const submitButton = e.submitter;
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> প্রসেসিং...';
        }

        return true;
    });
});
</script>
@endsection
