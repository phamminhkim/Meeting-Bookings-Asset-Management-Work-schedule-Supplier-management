<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Success Response
        Response::macro('success', function ($message, $data = null) {

            $response = ['success' => true, 'message' => $message];
            if ($data) {
                $response['data'] = $data;
            }
            return Response::json($response, 200);
        });
        // Failed Response
        Response::macro('fail', function ($message = 'Something went to wrong.', $statuscode = 200) {
            return Response::json(['success' => false, 'message' => $message , 'code' => 420], $statuscode);
        });

    }
public function success($data, $status = 200) {
        return [
            'data' => $data,
            'success' => in_array($status, [200, 201, 202]) ? true : false,
            'message' => null
        ];
    }

    public function failure($data, $status = 500) {
        // TODO
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}