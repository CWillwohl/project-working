<div>
    <nav role="navigation" aria-label="Pagination Navigation" class="join">
        <span>
            @if ($paginator->onFirstPage())
                <button disabled class="join-item btn btn-primary btn-outline">«</button>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" class="join-item btn btn-primary btn-outline">«</button>
            @endif
        </span>

        <button class="join-item btn btn-primary btn-outline">
            {{ $paginator->currentPage() }}
        </button>

        <span>
            @if ($paginator->onLastPage())
                <button disabled class="join-item btn btn-primary btn-outline">»</button>
            @else
                <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" class="join-item btn btn-primary btn-outline">»</button>
            @endif
        </span>
    </nav>
</div>
