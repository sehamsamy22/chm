<?php

namespace App\Modules\Contact\Http\Controllers\Admin;

use App\Modules\Contact\Repositories\ContactRepository;
use App\Modules\Contact\Jobs\SendEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Modules\Contact\Entities\Contact;
use App\Modules\Contact\Transformers\ContactResource;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    private $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
        $this->middleware('permission:contact-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:contact-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $contacts = $this->contactRepository->all();
        return $this->apiResponse(ContactResource::collection($contacts));
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        return $this->apiResponse(new ContactResource($contact));
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return $this->apiResponse(ContactResource::collection(Contact::all()));
    }

    /**
     *
     */
    public function sendReplies(Request $request)
    {
        $contacts = $request->all();
        SendEmail::dispatch($contacts);
        return $this->apiResponse("تم ارسال الرد");
    }
}
