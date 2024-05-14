<?php

namespace App\Http\Controllers;

use App\EML;
use App\Jobs\ProcessEmailMessage;
use App\Models\EmailMessage;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EmailMessageController extends Controller
{
    public function index()
    {
        return view('email_messages', []);
    }

    public function view(int $id): View
    {
        /** @var EmailMessage|null */
        $emailMessage = EmailMessage::query()->where('id', '=', $id)->get()->first();

        if (! $emailMessage) {
            abort(404);
        }

        return view('email_messages_view', [
            'emailMessage' => $emailMessage,
            'eml' => EML::parse(Storage::readStream($emailMessage->file_path)),
        ]);
    }

    public function viewHTML(int $id, Request $request): string
    {
        if ($request->header('sec-fetch-dest') !== 'iframe' && $request->query('override') !== '1') {
            return 'It is not safe to render untrusted HTML. If you really want to view it add <code>?override=1</code> to the URL.';
        }

        /** @var EmailMessage|null */
        $emailMessage = EmailMessage::query()->where('id', '=', $id)->get()->first();

        if (! $emailMessage) {
            abort(404);
        }

        $eml = EML::parse(Storage::readStream($emailMessage->file_path));

        return '<html><body>'.$eml->html.'</body></html>';
    }

    public function apiUpload(Request $request): JsonResponse
    {
        // TODO: add token management
        $user = User::query()->where('id', '=', 1)->first();

        /** @var UploadedFile[] */
        $files = $request->file();
        foreach ($files as $file) {
            $path = $file->store('files');

            /** @var EmailMessage */
            $emailMessage = $user->emailMessages()->create([
                'file_path' => $path,
                'subject' => $file->getClientOriginalName(),
                'processing_status' => 'PROCESSING',
            ]);

            ProcessEmailMessage::dispatchSync($emailMessage);
        }

        return new JsonResponse('done', Response::HTTP_CREATED);
    }
}
