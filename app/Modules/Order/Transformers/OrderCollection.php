<?php

namespace App\Modules\Order\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'orders' => $this->collection->transform(function ($order) {
                return [
                    'id' => $order->id,
                    'status' => $order->status,
                    'price' => $order->total,
                    'date' => $order->created_at->toDateString(),
                    'time' => $order->created_at->format('H:i:s'),

                ];
            }),
            'paginate' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'next_page_url' => $this->nextPageUrl(),
                'prev_page_url' => $this->previousPageUrl(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage()
            ]
        ];
    }

    public function withResponse($request, $response)
    {
        $originalContent = $response->getOriginalContent();
        unset($originalContent['links'], $originalContent['meta']);
        $response->setData($originalContent);
    }
}
