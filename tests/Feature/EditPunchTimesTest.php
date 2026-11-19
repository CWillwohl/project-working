<?php

use App\Livewire\PunchClock\EditTimes;
use App\Livewire\PunchClock\Periods;
use App\Livewire\PunchClock\Register;
use App\Models\PunchClock;
use App\Models\User;
use App\Models\WorkingPeriod;
use Livewire\Livewire;

function makePunchPeriod(User $user, bool $closed = true, bool $received = false): WorkingPeriod
{
    $project = $user->projects()->create(['name' => 'Projeto de teste', 'price_per_hour' => 100, 'price_to_receive' => $received || !$closed ? 0 : 100, 'total_received' => $received ? 100 : 0]);
    $in = PunchClock::create(['user_id' => $user->id, 'project_id' => $project->id, 'punch_type' => 0, 'punch_time' => '2026-11-18 09:00:00']);
    $out = $closed ? PunchClock::create(['user_id' => $user->id, 'project_id' => $project->id, 'punch_type' => 1, 'punch_time' => '2026-11-18 10:00:00']) : null;
    return WorkingPeriod::create(['user_id' => $user->id, 'project_id' => $project->id, 'punch_in_id' => $in->id, 'punch_out_id' => $out?->id, 'punch_in_time' => $in->punch_time, 'punch_out_time' => $out?->punch_time, 'time_worked' => $closed ? '01:00' : '00:00', 'value_to_receive' => $closed ? 100 : 0, 'received' => $received]);
}

it('updates both punches, the period and the pending project balance', function () {
    $this->withoutVite();
    $user = User::factory()->create();
    $period = makePunchPeriod($user);
    Livewire::actingAs($user)->test(EditTimes::class)->call('open', $period->id)
        ->set('start', '2026-11-18T08:30')->set('end', '2026-11-18T10:30')->call('save')->assertHasNoErrors();
    expect($period->fresh()->time_worked)->toBe('02:00')
        ->and($period->fresh()->value_to_receive)->toBe(200.0)
        ->and($period->project->fresh()->price_to_receive)->toBe(200.0)
        ->and($period->punchIn->fresh()->punch_time)->toBe('2026-11-18 08:30:00')
        ->and($period->punchOut->fresh()->punch_time)->toBe('2026-11-18 10:30:00');
});

it('moves received periods back to pending and rejects an invalid interval', function () {
    $this->withoutVite();
    $user = User::factory()->create();
    $period = makePunchPeriod($user, true, true);
    Livewire::actingAs($user)->test(EditTimes::class)->call('open', $period->id, 'end')
        ->set('end', '2026-11-18T08:00')->call('save')->assertHasErrors(['end'])
        ->set('end', '2026-11-18T09:30')->call('save')->assertHasNoErrors();
    expect($period->fresh()->time_worked)->toBe('00:30')
        ->and((bool) $period->fresh()->received)->toBeFalse()
        ->and($period->project->fresh()->total_received)->toBe(0.0)
        ->and($period->project->fresh()->price_to_receive)->toBe(50.0);
});

it('rejects moving the entry past the existing exit', function () {
    $this->withoutVite();
    $user = User::factory()->create();
    $period = makePunchPeriod($user);
    Livewire::actingAs($user)->test(EditTimes::class)->call('open', $period->id, 'start')
        ->assertSee('marcado como pendente')
        ->set('start', '2026-11-18T10:30')->call('save')->assertHasErrors(['end']);
    expect($period->fresh()->punch_in_time)->toBe('2026-11-18 09:00:00');
});

it('edits an open punch without closing its period', function () {
    $this->withoutVite();
    $user = User::factory()->create();
    $period = makePunchPeriod($user, false);
    Livewire::actingAs($user)->test(EditTimes::class)->call('open', $period->id, 'start')
        ->set('start', '2026-11-18T08:00')->call('save')->assertHasNoErrors();
    expect($period->fresh()->punch_out_time)->toBeNull()->and($period->fresh()->time_worked)->toBe('00:00');
});

it('limits selection and editing to the current user and lists only closed periods', function () {
    $this->withoutVite();
    $user = User::factory()->create();
    $period = makePunchPeriod($user);
    $open = makePunchPeriod($user, false);
    $other = makePunchPeriod(User::factory()->create());
    Livewire::actingAs($user)->test(Periods::class)->assertSee('Selecione um projeto')
        ->call('selectProject', $period->project_id)->assertSee('18/11/2026 10:00');
    Livewire::actingAs($user)->test(Periods::class)->call('selectProject', $open->project_id)->assertSee('Nenhum período fechado');
    Livewire::actingAs($user)->test(Register::class)->call('selectProject', $period->project_id)->assertSee('Editar');
    expect(fn () => Livewire::actingAs($user)->test(Periods::class)->call('selectProject', $other->project_id))
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
    expect(fn () => Livewire::actingAs($user)->test(EditTimes::class)->call('open', $other->id))
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
});
