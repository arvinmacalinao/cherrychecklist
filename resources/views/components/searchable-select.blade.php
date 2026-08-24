@props([
    'id',
    'options' => [],
    'valueField' => 'id',
    'labelField' => 'name',
    'placeholder' => 'Select Option',
    'wireModel',
])

<div wire:ignore>
    <select id="{{ $id }}" class="w-full">

        <option value="">
            {{ $placeholder }}
        </option>

        @foreach ($options as $option)
            <option value="{{ $option->{$valueField} }}">
                {{ $option->{$labelField} }}
            </option>
        @endforeach

    </select>
</div>

<script>
document.addEventListener('livewire:init', () => {

    if (document.getElementById('{{ $id }}').tomselect) {
        return;
    }

    new TomSelect('#{{ $id }}', {
        create: false,

        onChange: function(value) {
            @this.set('{{ $wireModel }}', value);
        }
    });

});
</script>
