<?php

namespace App\Livewire\Portal;

use App\Models\Department;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateTicket extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $description = '';
    public string $priority = 'medium';
    public ?int $department_id = null;
    public ?int $assigned_to = null;
    public array $attachments = [];

    protected $rules = [
        'title'           => 'required|min:5|max:255',
        'description'     => 'required|min:10',
        'priority'        => 'required|in:low,medium,high,critical',
        'department_id'   => 'nullable|exists:departments,id',
        'assigned_to'     => 'nullable|exists:users,id',
        'attachments.*'   => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:10240',
    ];

    public function submit()
    {
        $this->validate();

        $ticket = Ticket::create([
            'title'         => $this->title,
            'description'   => $this->description,
            'priority'      => $this->priority,
            'department_id' => $this->department_id,
            'assigned_to'   => $this->assigned_to,
            'created_by'    => Auth::id(),
            'status'        => 'open',
        ]);

        foreach ($this->attachments as $file) {
            $ticket->addMedia($file->getRealPath())
                ->usingFileName($file->getClientOriginalName())
                ->toMediaCollection('attachments');
        }

        session()->flash('success', 'Ticket creado correctamente. Te notificaremos cuando sea atendido.');

        return redirect()->route('portal.tickets');
    }

    public function render()
    {
        return view('livewire.portal.create-ticket', [
            'departments' => Department::where('is_active', true)->get(),
            'agents'      => User::role('agent')->get(),
        ]);
    }
}
