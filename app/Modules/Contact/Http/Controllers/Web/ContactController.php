<?php

namespace App\Modules\Contact\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Contact\Entities\Contact;
use App\Modules\Contact\Transformers\ContactResource;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $contact = Contact::create($request->all());
        return $this->apiResponse(new ContactResource($contact));
    }
}
