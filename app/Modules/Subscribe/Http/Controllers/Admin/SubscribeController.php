<?php

namespace App\Modules\Subscribe\Http\Controllers\Admin;

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
    public function __construct()
    {
        $this->middleware('permission:subscribe-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:subscribe-delete', ['only' => ['destroy']]);
    }
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $subscribers = Subscribe::all();
        return $this->apiResponse(SubscriberResource::collection($subscribers));
    }

    public function destroy($id): JsonResponse
    {
        $subscriber = Subscribe::findOrFail($id);
        $subscriber->delete();
        return $this->apiResponse(SubscriberResource::collection(Subscribe::all()));
    }

    public function sendEmails(Request $request): JsonResponse
    {
        $subscribers = Subscribe::all();
        SendEmail::dispatch($subscribers);
        return $this->apiResponse(__('general.sending'));
    }
}
