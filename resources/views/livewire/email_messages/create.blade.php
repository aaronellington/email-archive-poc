<?php

use App\Jobs\ProcessEmailMessage;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $message = '';
    public $iteration = 0;

    #[Validate('required|file|mimes:eml|max:1024')]
    public $files = [];

    public function save(): void
    {
        if (empty($this->files)) {
            return;
        }

        /** @var User */
        $user = auth()->user();

        foreach ($this->files as $file) {
            $path = $file->store('files');

            /** @var EmailMessage */
            $emailMessage = $user->emailMessages()->create([
                'file_path' => $path,
                'subject' => $file->getClientOriginalName(),
                'processing_status' => 'PROCESSING',
            ]);

            ProcessEmailMessage::dispatch($emailMessage);
        }

        $this->dispatch('email-message-submit-start');

        $this->files = [];
        $this->iteration++;
    }
}; ?>

<form wire:submit="save">
    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="upload{{ $iteration }}">
        Upload email message (.eml files):
    </label>
    <input wire:model="files" accept=".eml" id="upload{{ $iteration }}" name="files"
        class="block w-full text-sm
        text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none
        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
        type="file" multiple />

    <div>
        @error('files.*')
            <span class="error">{{ $message }}</span>
        @enderror
    </div>

    <div wire:loading wire:target="files">Uploading...</div>


    @if (count($files))
        <div wire:loading.remove wire:target="files">
            <x-primary-button class="mt-4" id="submit-form">{{ __('Submit Form') }}</x-primary-button>
        </div>
    @endif
</form>
