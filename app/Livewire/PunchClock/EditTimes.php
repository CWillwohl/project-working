<?php

namespace App\Livewire\PunchClock;

use App\Actions\PunchClock\UpdatePunchTimes;
use App\Models\WorkingPeriod;
use Livewire\Attributes\On;
use Livewire\Component;

class EditTimes extends Component
{
    public bool $openModal = false;
    public ?int $periodId = null;
    public ?string $start = null;
    public ?string $end = null;
    public bool $editStart = true;
    public bool $editEnd = true;
    public bool $closed = false;

    #[On('punch-clock::edit')]
    public function open(int $periodId, string $field = 'both'): void
    {
        abort_unless(in_array($field, ['start', 'end', 'both'], true), 404);
        $period = WorkingPeriod::where('user_id', auth()->id())->findOrFail($periodId);
        abort_if($field === 'end' && !$period->punch_out_id, 404);
        $this->resetValidation();
        $this->periodId = $period->id;
        $this->start = date('Y-m-d\TH:i', strtotime($period->punch_in_time));
        $this->end = $period->punch_out_time ? date('Y-m-d\TH:i', strtotime($period->punch_out_time)) : null;
        $this->closed = (bool) $period->punch_out_id;
        $this->editStart = $field !== 'end';
        $this->editEnd = $field !== 'start' && $this->closed;
        $this->openModal = true;
    }

    public function save(): void
    {
        $this->validate(['start' => 'required|date_format:Y-m-d\TH:i', 'end' => 'nullable|date_format:Y-m-d\TH:i']);
        $period = WorkingPeriod::where('user_id', auth()->id())->findOrFail($this->periodId);
        $start = $this->editStart ? $this->start : $period->punch_in_time;
        $end = $this->editEnd ? $this->end : $period->punch_out_time;
        (new UpdatePunchTimes())->execute($period->id, $start, $end);
        $this->openModal = false;
        $this->dispatch('punch-clock::updated');
        $this->dispatch('alert::handle', ['type' => 'success', 'message' => 'Registro atualizado com sucesso!']);
    }

    public function render()
    {
        return view('livewire.punch-clock.edit-times');
    }
}
