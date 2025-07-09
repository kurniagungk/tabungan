@props([
    'model',
    'label' => null,
    'placeholder' => '',
    'disabled' => false,
    'errorClass' => 'text-error text-sm mt-1',
])

@php
    $fieldName = $model;
@endphp

<div x-data="{
    format(num) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }).format(num);
        },
        unformat(str) {
            return parseInt(str.replace(/[^\d]/g, '')) || 0;
        },
        onInput(e) {
            const raw = this.unformat(e.target.value);
            $wire.set('{{ $model }}', raw);
            e.target.value = this.format(raw);
        },
        init() {
            const val = $wire.get('{{ $model }}') || 0;
            this.$refs.input.value = this.format(val);
        }
}" x-init="init" class="form-control w-full">
    <fieldset class="fieldset w-full">
        @if ($label)
            <legend class="fieldset-legend">{{ $label }}</legend>
        @endif

        {{-- Input teks tampilan --}}
        <input type="text" x-ref="input" x-on:input="onInput" inputmode="numeric" data-model="{{ $model }}"
            :disabled="{{ $disabled ? 'true' : 'false' }}" placeholder="{{ $placeholder }}"
            class="input w-full @error($fieldName) input-error @enderror" />

        {{-- Error Message --}}
        @error($fieldName)
            <p class="{{ $errorClass }}">{{ $message }}</p>
        @enderror
    </fieldset>
</div>
