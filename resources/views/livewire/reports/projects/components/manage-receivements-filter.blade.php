<div class="dropdown dropdown-bottom md:dropdown-start">
    <div tabindex="0" role="button" class="font-[600] text-white w-36 btn btn-success">
        <i class="fa-solid fa-filter"></i>
        Filtrar por:
    </div>
    <div tabindex="0" class="fixed top-0 left-0 z-50 w-48 gap-4 p-4 shadow dropdown-content menu bg-base-100 rounded-box md:w-96">
        <x-input error label labelText="Data inicial:" wire:model="dateStart" name="dateStart" type="date" placeholder="Pesquisar por projeto" class="w-full" />
        <x-input error label labelText="Data final:" wire:model="dateEnd" name="dateEnd" type="date" placeholder="Pesquisar por projeto" />
        <button type="button" class="btn btn-sm btn-outline" wire:click="preset(7)">Última semana</button>
        <button type="button" class="btn btn-sm btn-outline" wire:click="preset(15)">Últimos 15 dias</button>
        <button type="button" class="btn btn-sm btn-outline" wire:click="preset(30)">Último mês</button>
        <button class="text-white btn btn-primary" wire:click="filterData">Filtrar</button>
    </div>
</div>
