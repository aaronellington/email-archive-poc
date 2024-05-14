<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 dark:text-white bg-white dark:bg-gray-800 shadow sm:rounded-lg flex flex-col gap-4">
                <h1 class="text-2xl">{{ $emailMessage->subject }}</h1>
                <div>
                    <table class="text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <tbody>
                            <tr>
                                <th class="pr-2 text-right">Sender</th>
                                <td class="pr-2 text-left text-black dark:text-white">{{ $emailMessage->sender }}</td>
                            </tr>
                            <tr>
                                <th class="pr-2 text-right">Recipient</th>
                                <td class="pr-2 text-left text-black dark:text-white">{{ $emailMessage->recipient }}</td>
                            </tr>
                            <tr>
                                <th class="pr-2 text-right">Is Test</th>
                                <td class="pr-2 text-left text-black dark:text-white">
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
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="flex gap-2 mb-4">
                    @foreach ($eml->attachments as $attachment)
                        <div
                            class=" hover:bg-gray-700 p-2 cursor-pointer text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-900 dark:border-gray-700 flex flex-col items-center gap-2 w-44">

                            <svg xmlns="http://www.w3.org/2000/svg" class="fill-black dark:fill-white w-5"
                                viewBox="0 0 448 512">
                                <path
                                    d="M364.2 83.8c-24.4-24.4-64-24.4-88.4 0l-184 184c-42.1 42.1-42.1 110.3 0 152.4s110.3 42.1 152.4 0l152-152c10.9-10.9 28.7-10.9 39.6 0s10.9 28.7 0 39.6l-152 152c-64 64-167.6 64-231.6 0s-64-167.6 0-231.6l184-184c46.3-46.3 121.3-46.3 167.6 0s46.3 121.3 0 167.6l-176 176c-28.6 28.6-75 28.6-103.6 0s-28.6-75 0-103.6l144-144c10.9-10.9 28.7-10.9 39.6 0s10.9 28.7 0 39.6l-144 144c-6.7 6.7-6.7 17.7 0 24.4s17.7 6.7 24.4 0l176-176c24.4-24.4 24.4-64 0-88.4z" />
                            </svg>
                            <a download="{{ $attachment->name }}"
                                href="data:application/octet-stream;base64,{{ base64_encode($attachment->content) }}">
                                {{ $attachment->name }}
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="bg-orange-100 border-x-4 border-t-4 border-orange-500 text-orange-700 p-4 -mb-4"
                    role="alert">
                    <p class="font-bold	text-xl uppercase">Please proceed with caution.</p>
                    <p>A preview of the email is shown bellow. </p>
                    <p>The contents are <span class="font-bold	uppercase">untrusted</span> so it is displayed using a
                        <a class="underline" href="https://www.w3schools.com/tags/att_iframe_sandbox.asp"
                            target="_BLANK">sandboxed
                            iframe</a> to limit the potential risk.
                    </p>
                </div>

                <iframe sandbox width="100%" height="1024px" class="bg-white border-4 border-orange-500"
                    src="{{ route('email_messages_view_html', $emailMessage->id) }}" style="background: white"></iframe>
            </div>
        </div>
    </div>
</x-app-layout>
