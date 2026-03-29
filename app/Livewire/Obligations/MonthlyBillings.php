<?php

namespace App\Livewire\Obligations;

use App\Livewire\Forms\ObligationForm;
use App\Models\Obligation;
use Livewire\Component;

class MonthlyBillings extends Component
{
    public ObligationForm $form;

    public $totalObligation = 0;

    public $countObligation = 0;

    public $obligationModalIsOpen = false;

    public function openModal($obligationId = null)
    {
        if (!is_null($obligationId)) {
            $obligation = Obligation::where('id', $obligationId)
                ->where('user_id', auth()->id())
                ->firstOrFail();
            $this->form->setObligation($obligation);
        }
        $this->obligationModalIsOpen = true;
    }

    public function closeModal()
    {
        $this->form->reset();
        $this->obligationModalIsOpen = false;
    }

    public function save()
    {
        $obligation = $this->form->store();
        $this->form->createTransactions($obligation);
        $this->obligationModalIsOpen = false;
        session()->flash('message', 'Se ha creado una nueva obligación y sus cuentas por pagar cada mes.');
        $this->form->reset();
    }

    public function edit()
    {
        $this->authorize('update', $this->form->obligation);
        $this->form->update();
        session()->flash('message', 'Tu obligación ha sido modificada correctamente.');
        $this->obligationModalIsOpen = false;
        $this->form->reset();
    }

    public function changeStatus(Obligation $obligation)
    {
        $this->authorize('update', $obligation);
        $isActive = $obligation->is_active;
        $isActive
            ? $this->form->removeTransactions($obligation)
            : $this->form->createTransactions($obligation);

        $obligation->update(['is_active' => !$isActive]);
        session()->flash('message', 'Se ha ' . ($isActive ? 'desactivado' : 'activado') . ' tu obligación.');
    }

    public function delete(Obligation $obligation)
    {
        $this->authorize('delete', $obligation);
        $this->form->removeTransactions($obligation);
        $obligation->delete();
        session()->flash('message', 'Se ha eliminado la obligación y tus cuentas por pagar relacionadas.');
    }
    
    public function render()
    {
        $obligations = Obligation::where('user_id', auth()->id())->get();

        $this->totalObligation = (clone $obligations)->where('is_active', true)->sum('amount');
        $this->countObligation = count((clone $obligations)->where('is_active', true));

        return view('livewire.obligations.monthly-billings', compact('obligations'));
    }
}
