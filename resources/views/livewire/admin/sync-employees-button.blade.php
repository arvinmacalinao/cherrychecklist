<div class="inline-flex items-center gap-3">

   <a class="btn btn-primary btn-sm"
    wire:click="sync"
    wire:loading.attr="disabled"
    wire:target="sync"
    >

    <span wire:loading.remove wire:target="sync">
        Sync Employees
    </span>

    <span wire:loading wire:target="sync">
        Syncing...
    </span>

    </a>

    @if ($successMessage)
        <span class="text-success text-sm">{{ $successMessage }}</span>
    @endif

    @if ($errorMessage)
        <span class="text-error text-sm">{{ $errorMessage }}</span>
    @endif

</div>
