<?php

namespace App\Modules\Payment\Methods\Telr;

use Illuminate\Database\Eloquent\Model;

class TelrURL extends Model
{
    /**
     * @var string
     */
    protected $telrURL;

    /**
     * TelrURL constructor.
     *
     * @param array $url
     */
    public function __construct($url)
    {
        $this->telrURL = $url;
    }

    /**
     * Redirect response to telr URL
     *
     *
     */
    public function redirect()
    {
        return $this->response('يرجي اكمال عملية الدفع', ['pay_url' => $this->telrURL], 202);

    }

    /**
     * Get the redirect URL
     *
     * @return string
     */
    public function redirectURL()
    {
        return $this->telrURL;
    }

    protected function response($message, $data, $code, bool $success = true): array
    {
        return [
            'message' => $message,
            'data' => $data,
            'status_code' => $code,
            'success' => $success
        ];
    }
}
