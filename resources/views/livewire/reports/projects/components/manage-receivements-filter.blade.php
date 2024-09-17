<div class="dropdown dropdown-bottom md:dropdown-start">
    <div tabindex="0" role="button" class="font-[600] text-white w-36 btn btn-success">
        <i class="fa-solid fa-filter"></i>
        Filtrar por:
    </div>
    <div tabindex="0" class="fixed top-0 left-0 z-50 w-48 gap-4 p-4 shadow dropdown-content menu bg-base-100 rounded-box md:w-96">
        <x-input error label labelText="Data inicial:" wire:model="dateStart" name="dateStart" type="date" placeholder="Pesquisar por projeto" class="w-full" />
        <x-input error label labelText="Data final:" wire:model="dateEnd" name="dateEnd" type="date" placeholder="Pesquisar por projeto" />
        <x-select error label labelText="Status:" wire:model="received" name="received">
            <option value="">Todos</option>
            <option value="0">Pendente</option>
            <option value="1">Recebido</option>
        </x-select>
        <button class="text-white btn btn-primary" wire:click="filterData">Filtrar</button>
    </div>
</div>
