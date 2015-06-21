<?php

namespace App\Exceptions;

use Exception;
use Mail;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\Debug\ExceptionHandler as SymfonyDisplayer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Session\TokenMismatchException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
		if (!config('app.debug') && !$this->shouldntReport($e)) {
            $address = config('app.email_exceptions');
            $url = config('app.url');

            if ($address) {
                $exceptions = [];
                $i = $e;
                do {
                    $exceptions[] = $i;
                    $i = $i->getPrevious();
                } while ($i);

                Mail::send(
                    [ 'html' => 'emails.error' ],
                    [ 'exceptions' => $exceptions ],
                    function ($message) use ($address, $url)
                    {
                        $message->to($address)
                                ->subject("[Exception] {$url}");
                    }
                );
            }
        }

        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if (config('app.debug'))
            return $this->renderWithStackTrace($e);

        // Convert CSRF token mismatch to 400 Bad Request
        if ($e instanceof TokenMismatchException)
            $e = new BadRequestHttpException('Token mismatch', $e);

        return $this->renderDefault($e);
    }

    /**
     * Render exception with stack trace
     *
     * @param  \Exception  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderWithStackTrace(Exception $e)
    {
        return (new SymfonyDisplayer(true))->createResponse($e);
    }

    /**
     * Render exception without details
     *
     * @param  \Exception  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderDefault(Exception $e)
    {
        $status = HttpResponse::HTTP_INTERNAL_SERVER_ERROR;
        if ($this->isHttpException($e))
            $status = $e->getStatusCode();

        $params = [
            'status' => $status,
            'phrase' => @HttpResponse::$statusTexts[$status],
        ];

        $view = 'errors.default';
        if (view()->exists("errors.{$status}"))
            $view = "errors.{$status}";

        return response()->view($view, $params, $status);
    }
}
