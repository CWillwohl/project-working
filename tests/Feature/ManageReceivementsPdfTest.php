<?php

use App\Livewire\Reports\Projects\ManageReceivements;
use App\Models\User;
use Livewire\Livewire;

it('downloads a PDF with valid file bytes through Livewire', function () {
    $this->withoutVite();

    $user    = User::factory()->create();
    $project = $user->projects()->create(['name' => 'Ateliê Horizonte', 'price_per_hour' => '140']);

    $component = Livewire::actingAs($user)
        ->test(ManageReceivements::class)
        ->call('selectProject', $project->id)
        ->call('generatePdf')
        ->assertFileDownloaded('Relatório de Períodos de Trabalho.pdf', null, 'application/pdf');

    $pdf = base64_decode($component->effects['download']['content'], true);

    expect($pdf)->toStartWith('%PDF-');
});

it('selects completed pending periods and updates the selection when filters change', function () {
    $this->withoutVite();
    $user    = User::factory()->create();
    $project = $user->projects()->create(['name' => 'Projeto', 'price_per_hour' => '100']);

    $createPeriod = function (string $date, bool $received, bool $completed) use ($user, $project) {
        $punch = \Illuminate\Support\Facades\DB::table('punch_clocks')->insertGetId([
            'user_id'    => $user->id, 'project_id' => $project->id,
            'punch_time' => $date . ' 09:00:00', 'punch_type' => 0,
        ]);

        return \App\Models\WorkingPeriod::create([
            'user_id'        => $user->id, 'project_id' => $project->id,
            'punch_in_id'    => $punch, 'punch_out_id' => $completed ? $punch : null,
            'punch_in_time'  => $date . ' 09:00:00',
            'punch_out_time' => $completed ? $date . ' 10:00:00' : null,
            'received'       => $received, 'value_to_receive' => 100,
        ]);
    };

    $pending = $createPeriod('2026-11-10', false, true);
    $createPeriod('2026-11-11', false, false);
    $received = $createPeriod('2026-11-12', true, true);

    Livewire::actingAs($user)->test(ManageReceivements::class)
        ->call('selectProject', $project->id)
        ->assertSet('received', 0)
        ->assertSet('selectedWorkedPeriods', [$pending->id])
        ->assertDontSee(date('d/m/Y H:i', strtotime($received->punch_in_time)))
        ->call('filterStatus', 1)
        ->assertSet('selectedWorkedPeriods', [])
        ->assertSee(date('d/m/Y H:i', strtotime($received->punch_in_time)))
        ->call('filterStatus', 0)
        ->dispatch('manage-receivements::filter-data', data: ['dateStart' => '2026-11-11', 'dateEnd' => '2026-11-18'])
        ->assertSet('selectedWorkedPeriods', []);
});

it('applies date shortcuts including today', function () {
    \Illuminate\Support\Carbon::setTestNow('2026-11-18 12:00:00');

    Livewire::test(\App\Livewire\Reports\Projects\Components\ManageReceivementsFilter::class)
        ->call('preset', 7)
        ->assertSet('dateStart', '2026-11-12')
        ->assertSet('dateEnd', '2026-11-18')
        ->call('preset', 15)
        ->assertSet('dateStart', '2026-11-04')
        ->call('preset', 30)
        ->assertSet('dateStart', '2026-10-20');

    \Illuminate\Support\Carbon::setTestNow();
});
