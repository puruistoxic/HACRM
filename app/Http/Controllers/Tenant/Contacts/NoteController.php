<?php

namespace App\Http\Controllers\Tenant\Contacts;

use Illuminate\Http\Request;
use App\Filters\Tenant\NoteFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Contact\NoteRequest;
use App\Models\Tenant\Customer\Note;
use App\Services\Tenant\Contact\ContactPersonNoteService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Core\File\File;

class NoteController extends Controller
{

    public function __construct(ContactPersonNoteService $service, NoteFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index($type): LengthAwarePaginator
    {
        //
        return $this->service
            ->select('id', 'user_id', 'contact_person_id', 'title', 'description')
            ->filters($this->filter)
            ->where($type . "_id", '!=', null)
            ->with($type)
            ->orderByDesc('id')
            ->paginate(
                request()->get('per_page', 10)
            );
    }

    public function store(NoteRequest $request)
    {
        DB::transaction(
            function () use ($request) {
                $note = $this->service
                    ->save(
                        $request->only(
                            'id',
                            'user_id',
                            'contact_person_id',
                            'title',
                            'description'
                        )
                    );
                if ($request->attachments) {
                    $this->service
                        ->setModel($note)
                        ->setAttrs($request->only('attachments'))
                        ->storeAttachments();
                }
            }
        );
        return created_responses('note');
    }

    public function show($type, Note $note): Note
    {
        return $note->load('contactperson:id,name', 'attachments');
        // return $note->load('note:id,title', 'attachments');
        // return $note;
    }


    public function update($type, NoteRequest $request, Note $note)
    {
        $this->service
            ->setModel($note)
            ->save($request->only(
                'id',
                'user_id',
                'contact_person_id',
                'title',
                'description'
            ));


        if ($request->only('attachments')) {
            $this->service
                ->setModel($note)
                ->setAttrs($request->only('attachments'))
                ->storeAttachments();
        }
        return updated_responses('note');
    }

    public function destroy($type, Note $note): array
    {
        $note->delete();

        $this->service
            ->setModel($note)
            ->deleteAttachmentFile($note->attachments)
            ->delete();

        return deleted_responses('note');
    }

    public function noteById($note)
    {
        return ['data' => $this->service
            ->findNote($note)->load('attachments')];
        // return 'hello';
    }

    public function noteId($note)
    {
        return ['data' => $this->service
            ->findNoteId($note)];
        // return 'hello';
    }

    public function deleteFile($id)
    {
        $file = File::query()
            ->where('id', $id)
            ->where('type', 'note')
            ->first();

        if (isset($file->path)) {
            $this->service->deleteImage($file->path);
            $file->delete();
            return deleted_responses('Note attachment');
        } else {
            return failed_responses();
        }
    }
}
