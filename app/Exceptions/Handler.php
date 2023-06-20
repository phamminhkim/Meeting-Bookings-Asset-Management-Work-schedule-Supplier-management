<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        
        if($exception instanceof AuthorizationException && $request->ajax()){
          
            return response()->json(['statuscode'=>'403','message'=>'Not authorized.'],403);
        }
        //|| $exception instanceof  
        if ($exception instanceof TokenMismatchException  //Lỗi này hết hạn        
        )  //Lỗi này phản hồi
        { 
            return redirect()->route('login');
        }
        
        //Kiểm tra giới hạn upload file so với giá trị post_max_size  ( trong php.ini)
        if($exception instanceof FileException  && $request->ajax()){
           // dd("teeee");
            return response()->json(['statuscode'=>'422','message'=>'File too large.'],422);
        }
        return parent::render($request, $exception);
    }

    // protected function unauthenticated($request, AuthenticationException $exception)
    // {
    //     return $request->expectsJson()
    //         ? response()->json(['message' => $exception->getMessage()], 401)
    //         : redirect()->guest(route('login', ['account' => $request->route('account')]));
    // }
}
