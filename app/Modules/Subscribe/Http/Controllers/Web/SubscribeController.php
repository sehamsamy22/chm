<?php

namespace App\Modules\Subscribe\Http\Controllers\Web;
use App\Modules\Subscribe\Jobs\SendEmail;
use App\Modules\Subscribe\Entities\Subscribe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Modules\Subscribe\Repositories\SubscribeRepository;
use App\Modules\Subscribe\Transformers\SubscriberResource;

class SubscribeController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email|max:255|unique:subscribers',
        ]);
        $subscriber = Subscribe::create($validated);
        return $this->apiResponse( new SubscriberResource($subscriber));
    }


}
