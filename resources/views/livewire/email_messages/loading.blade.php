<?php

use App\Models\EmailMessage;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public $processingEmailMessages = [];

    public function mount()
    {
        $this->checkQueues();
    }

    #[On('email-message-submit-start')]
    public function checkQueues(): void
    {
        $previousValue = count($this->processingEmailMessages);
        $new = EmailMessage::query()->where('processing_status', '=', 'PROCESSING')->get();

        if ($previousValue != count($new)) {
            $this->dispatch('email-message-submit-complete');
        }

        $this->processingEmailMessages = $new;
    }
}; ?>

<div>
    @unless (count($processingEmailMessages) === 0)
        <div wire:poll.1s="checkQueues"></div>
    @endunless
</div>
