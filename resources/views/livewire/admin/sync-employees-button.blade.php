<div class="inline-block">

   <a class="btn btn-primary btn-sm"
    wire:click="sync"
    wire:loading.attr="disabled"
    >

    <span wire:loading.remove>
        Sync Employees
    </span>

    <span wire:loading>
        Syncing...
    </span>

    </a>

</div>
