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
