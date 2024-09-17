<div>
    @if(isset($alert['type']) && $alert['type'] === 'success')
        <div role="alert" class="mb-4 font-medium text-green-900 bg-green-500/30 alert">
            <i class="flex items-center justify-center w-8 h-8 p-2 border-2 border-green-900 rounded-full fa-solid fa-check"></i>
            <span>
                {{ $alert['message'] }}
            </span>
        </div>
    @endif
    @if(isset($alert['type']) && $alert['type'] === 'error')
        <div role="alert" class="mb-4 font-medium text-red-900 bg-red-500/30 alert">
            <i class="flex items-center justify-center w-8 h-8 p-2 border-2 border-red-900 rounded-full fa-solid fa-xmark"></i>
            <span>
                {{ $alert['message'] }}
            </span>
        </div>
    @endif
    @if(isset($alert['type']) && $alert['type'] === 'info')
        <div role="alert" class="mb-4 font-medium text-sky-900 bg-sky-500/30 alert">
            <i class="flex items-center justify-center w-8 h-8 p-2 text-center border-2 rounded-full border-sky-900 fa-solid fa-exclamation"></i>
            <span>
                {{ $alert['message'] }}
            </span>
        </div>
    @endif
</div>
