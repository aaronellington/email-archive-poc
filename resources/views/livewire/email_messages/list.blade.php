<?php

use App\Models\EmailMessage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public Collection $emailMessages;

    protected $listeners = ['refreshProducts' => '$refresh'];

    public function mount(): void
    {
        $this->getEmailMessages();
    }

    #[On('email-message-submit-start')]
    #[On('email-message-submit-complete')]
    public function getEmailMessages(): void
    {
        $this->emailMessages = EmailMessage::latest()->get();
    }

    public function delete(EmailMessage $email): void
    {
        Storage::delete($email->file_path);
        $email->delete();
        $this->getEmailMessages();
    }
}; ?>

<div class="relative overflow-x-auto ">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mb-32">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr class="whitespace-nowrap">
                <th class="px-3 py-2">Actions</th>
                <th class="px-3 py-2">Status</th>
                <th class="px-3 py-2">Subject</th>
                <th class="px-3 py-2">Sender</th>
                <th class="px-3 py-2">Recipient</th>
                <th class="px-3 py-2">Attachments</th>
                <th class="px-3 py-2">Is test</th>
            </tr>
        </thead>
        <tbody>
            @if (count($emailMessages) === 0)
                <tr
                    class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <td colspan="8" class="text-center p-10">No Results</td>
                </tr>
            @endif
            @foreach ($emailMessages as $emailMessage)
                <tr
                    class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <td class="px-3 py-2">
                        @if ($emailMessage->processing_status === 'COMPLETE')
                            <x-dropdown align="left">
                                <x-slot name="trigger">
                                    <button class="row-action-menu">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('email_messages_view', $emailMessage->id)" wire:navigate
                                        class="cursor-pointer row-view-button">
                                        {{ __('View') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link wire:click.prevent="delete({{ $emailMessage }})"
                                        class="cursor-pointer">
                                        {{ __('Delete') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        @elseif ($emailMessage->processing_status === 'FAILED')
                            <x-dropdown align="left">
                                <x-slot name="trigger">
                                    <button>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link wire:click="delete({{ $emailMessage }})" class="cursor-pointer">
                                        {{ __('Delete') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        @endif
                    </td>
                    <td class="px-3 py-2">
                        <div class="flex items-center" title="{{ $emailMessage->processing_result }}">
                            @switch($emailMessage->processing_status)
                                @case('COMPLETE')
                                    <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div>
                                @break

                                @case('PROCESSING')
                                    <div class="h-2.5 w-2.5 rounded-full bg-yellow-500 me-2"></div>
                                @break

                                @case('FAILED')
                                    <div class="h-2.5 w-2.5 rounded-full bg-red-500 me-2"></div>
                                @break
                            @endswitch
                            {{ $emailMessage->processing_status }}
                        </div>
                    </td>
                    <td class="px-3 py-2 ">
                        <div class="text-ellipsis whitespace-nowrap max-w-64 overflow-x-hidden"
                            title="{{ $emailMessage->subject }}">
                            {{ $emailMessage->subject }}
                        </div>
                    </td>
                    <td class="px-3 py-2">{{ $emailMessage->sender }}</td>
                    <td class="px-3 py-2">{{ $emailMessage->recipient }}</td>

                    <td class="px-3 py-2">
                        <div class="flex justify-center">
                            @if ($emailMessage->processing_status === 'COMPLETE')
                                @if ($emailMessage->has_attachments)
                                    <svg class="w-3 h-3 text-green-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                                    </svg>
                                @else
                                    <svg class="w-3 h-3 text-red-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                @endif
                            @endif
                        </div>
                    </td>
                    <td class="px-3 py-2">
                        <div class="flex justify-center">
                            @if ($emailMessage->processing_status === 'COMPLETE')
                                @if ($emailMessage->is_test)
                                    <svg class="w-3 h-3 text-green-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                                    </svg>
                                @else
                                    <svg class="w-3 h-3 text-red-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
