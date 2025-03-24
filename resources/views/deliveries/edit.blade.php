@extends('layout')

@section('title', 'Edit Delivery Record - NSBM')
@section('header', 'Edit Delivery Record')

@section('content')
<div class="mb-6">
    <a href="{{ route('delivery.index') }}" class="text-secondary hover:text-secondary-dark">
        <i class="fas fa-arrow-left mr-2"></i>Back to Delivery Records
    </a>
</div>

<div class="dashboard-card p-6 mb-6">
    <div class="border-b pb-4 mb-6" style="border-color: var(--color-border);">
        <div class="flex items-center">
            <div class="rounded-full p-2 mr-3" style="background-color: var(--color-primary-light);">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: var(--color-primary);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-medium" style="color: var(--color-text-primary);">{{ $ward->name }}</h3>
                <p class="text-sm" style="color: var(--color-text-secondary);">Edit delivery record for {{ $delivery->report_date->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <form action="{{ route('delivery.update', $delivery->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Date Selection -->
        <div class="mb-6">
            <label for="report_date" class="block mb-2" style="color: var(--color-text-primary);">Report Date</label>
            <input type="date" name="report_date" id="report_date"
                   class="w-full px-4 py-2 border rounded-md focus:outline-none"
                   style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                   value="{{ old('report_date', $delivery->report_date->format('Y-m-d')) }}" required>
            <p class="text-sm mt-1" style="color: var(--color-text-secondary);">
                Date for this delivery report
            </p>
            @error('report_date')
                <p class="text-accent text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Delivery Types Section -->
        <div class="border-t pt-6 mb-6" style="border-color: var(--color-border);">
            <h3 class="text-lg font-medium mb-4" style="color: var(--color-text-primary);">Delivery Types</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label for="svd" class="block mb-2" style="color: var(--color-text-primary);">SVD</label>
                    <input type="number" name="svd" id="svd"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                           value="{{ old('svd', $delivery->svd) }}" required min="0">
                    @error('svd')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="lscs" class="block mb-2" style="color: var(--color-text-primary);">LSCS</label>
                    <input type="number" name="lscs" id="lscs"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                           value="{{ old('lscs', $delivery->lscs) }}" required min="0">
                    @error('lscs')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="vacuum" class="block mb-2" style="color: var(--color-text-primary);">Vacuum</label>
                    <input type="number" name="vacuum" id="vacuum"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                           value="{{ old('vacuum', $delivery->vacuum) }}" required min="0">
                    @error('vacuum')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="forceps" class="block mb-2" style="color: var(--color-text-primary);">Forceps</label>
                    <input type="number" name="forceps" id="forceps"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                           value="{{ old('forceps', $delivery->forceps) }}" required min="0">
                    @error('forceps')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="breech" class="block mb-2" style="color: var(--color-text-primary);">Breech</label>
                    <input type="number" name="breech" id="breech"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                           value="{{ old('breech', $delivery->breech) }}" required min="0">
                    @error('breech')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="eclampsia" class="block mb-2" style="color: var(--color-text-primary);">Eclampsia</label>
                    <input type="number" name="eclampsia" id="eclampsia"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                           value="{{ old('eclampsia', $delivery->eclampsia) }}" required min="0">
                    @error('eclampsia')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="twin" class="block mb-2" style="color: var(--color-text-primary);">Twin</label>
                    <input type="number" name="twin" id="twin"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                           value="{{ old('twin', $delivery->twin) }}" required min="0">
                    @error('twin')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="mrp" class="block mb-2" style="color: var(--color-text-primary);">MRP</label>
                    <input type="number" name="mrp" id="mrp"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                           value="{{ old('mrp', $delivery->mrp) }}" required min="0">
                    @error('mrp')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="fsb_mbs" class="block mb-2" style="color: var(--color-text-primary);">FSB/MBS</label>
                    <input type="number" name="fsb_mbs" id="fsb_mbs"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                           value="{{ old('fsb_mbs', $delivery->fsb_mbs) }}" required min="0">
                    @error('fsb_mbs')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="bba" class="block mb-2" style="color: var(--color-text-primary);">BBA</label>
                    <input type="number" name="bba" id="bba"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                           value="{{ old('bba', $delivery->bba) }}" required min="0">
                    @error('bba')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Additional Notes -->
        <div class="mb-6">
            <label for="notes" class="block mb-2" style="color: var(--color-text-primary);">Additional Notes</label>
            <textarea name="notes" id="notes" rows="3"
                      class="w-full px-4 py-2 border rounded-md focus:outline-none"
                      style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);">{{ old('notes', $delivery->notes) }}</textarea>
            <p class="text-sm mt-1" style="color: var(--color-text-secondary);">
                Any additional information about these deliveries
            </p>
            @error('notes')
                <p class="text-accent text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Info Section -->
        <div class="mb-6 p-4 rounded-lg border" style="background-color: var(--color-primary-light); border-color: var(--color-border);">
            <h4 class="font-medium mb-2" style="color: var(--color-secondary);">Information</h4>
            <p class="text-sm" style="color: var(--color-text-primary);">
                The total count of deliveries will be calculated automatically. Please ensure that all entries are correct before submitting.
            </p>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-between items-center">
            <a href="{{ route('delivery.show', $delivery->id) }}" class="btn btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-2"></i> Update Record
            </button>
        </div>
    </form>
</div>
@endsection
