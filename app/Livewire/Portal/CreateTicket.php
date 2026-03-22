<?php

namespace App\Livewire\Portal;

use App\Models\Department;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateTicket extends Component
{
    public string $title = '';
    public string $description = '';
    public string $priority = 'medium';
    public ?int $department_id = null;

    protected $rules = [
        'title'         => 'required|min:5|max:255',
        'description'   => 'required|min:10',
        'priority'      => 'required|in:low,medium,high,critical',
        'department_id' => 'nullable|exists:departments,id',
    ];

    public function submit()
    {
        $this->validate();

        Ticket::create([
            'title'         => $this->title,
            'description'   => $this->description,
            'priority'      => $this->priority,
            'department_id' => $this->department_id,
            'created_by'    => Auth::id(),
            'status'        => 'open',
        ]);

        session()->flash('success', 'Ticket creado correctamente. Te notificaremos cuando sea atendido.');

        return redirect()->route('portal.tickets');
    }

    public function render()
    {
        return view('livewire.portal.create-ticket', [
            'departments' => Department::where('is_active', true)->get(),
        ]);
    }
}
