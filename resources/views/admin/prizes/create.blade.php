@extends('layouts.app')

@section('title', 'নতুন পুরস্কার টেমপ্লেট')

@section('content')
<h2>নতুন পুরস্কার টেমপ্লেট তৈরি করুন</h2>
<hr>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.prizes.store') }}" id="prizeForm">
            @csrf
            <div class="mb-3">
                <label class="form-label">টেমপ্লেট নাম</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div id="prizesContainer">
                <div class="prize-item border p-3 mb-3 rounded">
                    <h6>পুরস্কার #1</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">পজিশন</label>
                            <input type="number" name="prizes[0][position]" class="form-control" value="1" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">পুরস্কারের নাম</label>
                            <input type="text" name="prizes[0][prize_name]" class="form-control" 
                                   placeholder="যেমন: ব্যাগ" required>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-secondary mb-3" onclick="addPrize()">আরও পুরস্কার যোগ করুন</button>
            
            <hr>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">সংরক্ষণ করুন</button>
                <a href="{{ route('admin.prizes.templates') }}" class="btn btn-secondary">বাতিল</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let prizeCount = 1;

function addPrize() {
    const container = document.getElementById('prizesContainer');
    const newPrize = document.createElement('div');
    newPrize.className = 'prize-item border p-3 mb-3 rounded position-relative';
    newPrize.innerHTML = `
        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" 
                onclick="this.parentElement.remove()">×</button>
        <h6>পুরস্কার #${prizeCount + 1}</h6>
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">পজিশন</label>
                <input type="number" name="prizes[${prizeCount}][position]" class="form-control" 
                       value="${prizeCount + 1}" required>
            </div>
            <div class="col-md-8">
                <label class="form-label">পুরস্কারের নাম</label>
                <input type="text" name="prizes[${prizeCount}][prize_name]" class="form-control" 
                       placeholder="যেমন: ঘড়ি" required>
            </div>
        </div>
    `;
    container.appendChild(newPrize);
    prizeCount++;
}
</script>
@endsection