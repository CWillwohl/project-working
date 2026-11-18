<?php

use App\Actions\PunchClock\RegisterNewPunchClockAction;
use App\Livewire\Auth\PasswordRecover;
use App\Mail\PasswordRecoveryToken;
use App\Models\User;
use Illuminate\Support\Facades\{Mail, Queue};
use Livewire\Livewire;

it('records punches, completes the period and updates the balance in the same request', function () {
    Queue::fake();
    $user    = User::factory()->create();
    $project = $user->projects()->create(['name' => 'Projeto', 'price_per_hour' => '50']);
    $this->actingAs($user);

    $this->travelTo(now()->startOfHour());
    (new RegisterNewPunchClockAction($project))->execute();
    expect($project->workedPeriods()->whereNull('punch_out_id')->count())->toBe(1);

    $this->travel(2)->hours();
    (new RegisterNewPunchClockAction($project))->execute();

    $period = $project->workedPeriods()->sole();

    expect($period->punch_out_id)->not->toBeNull()
        ->and((float) $period->value_to_receive)->toBe(100.0)
        ->and($project->refresh()->price_to_receive)->toBe(100.0);

    $period->update(['description' => 'Revisão']);
    expect($project->refresh()->price_to_receive)->toBe(100.0);
    Queue::assertNothingPushed();
});

it('sends the password recovery email in the same request', function () {
    Mail::fake();
    Queue::fake();
    $user = User::factory()->create();

    Livewire::test(PasswordRecover::class)
        ->set('email', $user->email)
        ->call('send')
        ->assertSet('sended', true);

    Mail::assertSent(PasswordRecoveryToken::class, fn (PasswordRecoveryToken $mail) => $mail->hasTo($user->email));
    Queue::assertNothingPushed();
});

it('keeps password recovery on the email step when delivery fails', function () {
    $user = User::factory()->create();
    Mail::shouldReceive('to')
        ->with($user->email)
        ->andThrow(new \Symfony\Component\Mailer\Exception\TransportException('SMTP unavailable'));

    Livewire::test(PasswordRecover::class)
        ->set('email', $user->email)
        ->call('send')
        ->assertHasErrors('email')
        ->assertSet('sended', false);
});
