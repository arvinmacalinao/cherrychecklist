@props([
    'id',
    'options' => [],
    'valueField' => 'id',
    'labelField' => 'name',
    'placeholder' => 'Select Option',
    'wireModel',
    'selected' => null,
])

<div wire:ignore>
    <select id="{{ $id }}" class="w-full">

        <option value="">
            {{ $placeholder }}
        </option>

        @foreach ($options as $option)
            <option
                value="{{ $option->{$valueField} }}"
                @selected($selected !== null && (string) $selected === (string) $option->{$valueField})>
                {{ $option->{$labelField} }}
            </option>
        @endforeach

    </select>
</div>

<script>
(function () {
    function initSelect() {
        const el = document.getElementById('{{ $id }}');

        // Element not on this page, or already initialized — TomSelect
        // marks the original <select> with a `.tomselect` reference.
        if (!el || el.tomselect) {
            return;
        }

        new TomSelect(el, {
            create: false,

            onChange: function (value) {
                @this.set('{{ $wireModel }}', value || null);
            },
        });
    }

    // livewire:init covers a normal full page load; livewire:navigated
    // covers Livewire's SPA-style navigation, if it's ever enabled — either
    // way initSelect() is a no-op once the TomSelect instance already exists.
    document.addEventListener('livewire:init', initSelect);
    document.addEventListener('livewire:navigated', initSelect);

    // Explicit reset hook: a parent component dispatches this (with this
    // select's id) after e.g. successfully saving a form, so it can clear
    // the pick without destroying/reinitializing the widget. wire:ignore
    // means Livewire's own reset()/property changes never reach this DOM —
    // TomSelect keeps its own JS-side state, so only its own API can clear
    // it. Registered once per instance; cheap no-op for every other id.
    document.addEventListener('livewire:init', () => {
        Livewire.on('reset-searchable-select', (event) => {
            const targetId = event.id ?? event[0]?.id;

            if (targetId !== '{{ $id }}') {
                return;
            }

            const el = document.getElementById('{{ $id }}');

            if (el && el.tomselect) {
                el.tomselect.clear();
            }
        });
    });
})();
</script>
