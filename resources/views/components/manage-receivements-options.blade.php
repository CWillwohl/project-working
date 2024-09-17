<div id="manage-receivements-options" class="dropdown dropdown-bottom md:dropdown-end">
    <div tabindex="0" role="button" class="font-[600] text-white w-36 btn btn-primary">
        <i class="fa-solid fa-gear"></i>
        Opções:
    </div>
    <ul tabindex="0" class="dropdown-content z-[1] menu p-4 shadow bg-base-100 rounded-box w-[300px]">
        <p class="font-medium">Registrar pagamento como:</p>
        <hr class="my-2">
        <li><a wire:click="openModal('received')">Recebido</a></li>
        <li><a wire:click="openModal('pending')">Pendente</a></li>
        <p class="mt-4 font-medium">Emitir documento dos registros como:</p>
        <hr class="my-2">
        <li><a wire:click="generatePdf">PDF</a></li>
        {{-- TODO --}}
        {{-- <li><a>Excel</a></li> --}}
        {{-- <li><a>CSV</a></li> --}}
    </ul>
</div>
