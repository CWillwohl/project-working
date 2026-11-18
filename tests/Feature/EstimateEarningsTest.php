<?php

use App\Livewire\Reports\Projects\EstimateEarnings;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user    = User::factory()->create();
    $this->project = $this->user->projects()->create(['name' => 'Projeto A', 'price_per_hour' => '50.25']);
    $this->actingAs($this->user);
});

it('opens the report with no project selected', function () {
    $this->withoutVite()
        ->get(route('reports.projects.estimate-earnings'))
        ->assertOk()
        ->assertSee('Selecione um projeto para estimar os ganhos.')
        ->assertDontSee('Ganhos estimados:');
});

it('updates the inclusive count and estimate and applies only saved exclusions', function () {
    Livewire::test(EstimateEarnings::class)
        ->call('selectProject', $this->project->id)
        ->set('dateStart', '2026-01-01')
        ->set('dateEnd', '2026-01-31')
        ->call('incrementTimeWorked')
        ->assertSee('R$ 1.557,75')
        ->call('toggleWeekends')
        ->assertSet('excludedWeekdays', [6, 7])
        ->assertSee('R$ 1.557,75')
        ->call('saveWeekdays')
        ->assertSee('R$ 1.105,50')
        ->set('excludedWeekdays', ['5', '6', '7'])
        ->call('saveWeekdays')
        ->assertSee('R$ 854,25')
        ->call('generatePdf')
        ->assertFileDownloaded('estimativa-de-ganhos.pdf', contentType: 'application/pdf');

    expect($this->project->fresh()->excluded_weekdays)->toBe([5, 6, 7]);
});

it('loads the saved weekdays for each project and handles an excluded single day', function () {
    $other                            = $this->user->projects()->create(['name' => 'Projeto B', 'price_per_hour' => '50.25']);
    $this->project->excluded_weekdays = [4];
    $this->project->save();

    Livewire::test(EstimateEarnings::class)
        ->set('dateStart', '2026-01-01')
        ->set('dateEnd', '2026-01-01')
        ->call('incrementTimeWorked')
        ->call('selectProject', $this->project->id)
        ->assertSet('excludedWeekdays', [4])
        ->assertSeeHtml('aria-label="Dias previstos de trabalho">0</output>')
        ->call('selectProject', $other->id)
        ->assertSet('excludedWeekdays', [])
        ->assertSeeHtml('aria-label="Dias previstos de trabalho">1</output>')
        ->call('selectProject', $this->project->id)
        ->assertSet('excludedWeekdays', [4]);
});

it('rejects inverted dates and invalid weekday selections', function () {
    Livewire::test(EstimateEarnings::class)
        ->call('selectProject', $this->project->id)
        ->set('dateStart', '2026-01-31')
        ->set('dateEnd', '2026-01-01')
        ->call('generatePdf')
        ->assertHasErrors(['dateEnd' => 'after_or_equal'])
        ->set('excludedWeekdays', [8])
        ->call('saveWeekdays')
        ->assertHasErrors(['excludedWeekdays.0' => 'between']);

    expect($this->project->fresh()->excluded_weekdays)->toBeNull();
});

it('denies selection of a project from another user', function () {
    $other = User::factory()->create()->projects()->create(['name' => 'Projeto B', 'price_per_hour' => '50.25']);

    Livewire::test(EstimateEarnings::class)
        ->call('selectProject', $other->id)
        ->assertForbidden();
});
