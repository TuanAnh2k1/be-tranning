<?php

namespace Mume\Core\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Mume\Core\Entities\DataResultCollection;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CoreHandler extends ExceptionHandler
{
    /**
     * @param             $request
     * @param  Throwable  $e
     *
     * @return JsonResponse|Response
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * @param             $request
     * @param  Throwable  $e
     *
     * @return JsonResponse
     */
    private function handleApiException($request, Throwable $e): JsonResponse
    {
        $e = $this->prepareException($e);
        if ($e instanceof HttpResponseException) {
            $e = $e->getResponse();
        }

        if ($e instanceof AuthenticationException) {
            $e = $this->unauthenticated($request, $e);
        }

        if ($e instanceof ValidationException) {
            return $this->handleApiUnprocessable($e);
        }

        return $this->customApiResponse($e);
    }

    /**
     * @param $e
     *
     * @return JsonResponse
     */
    private function customApiResponse($e): JsonResponse
    {
        $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
        $response = new DataResultCollection();
        switch ($statusCode) {
            case Response::HTTP_UNAUTHORIZED:
                $response->msg = trans('common.unauthenticated');
                break;
            case Response::HTTP_FORBIDDEN:
                $response->msg = 'Forbidden';
                break;
            case Response::HTTP_NOT_FOUND:
                $response->msg = 'Not Found';
                break;
            case Response::HTTP_METHOD_NOT_ALLOWED:
                $response->msg = 'Method Not Allowed';
                break;
            default:
                $response->msg = ($statusCode == Response::HTTP_INTERNAL_SERVER_ERROR) ? 'Có lỗi xảy ra trên server' : $e->getMessage();
                break;
        }

        $response->status = false;

        return response()->json($response, $statusCode);
    }

    /**
     * @param  ValidationException  $validator
     *
     * @return JsonResponse
     */
    public function handleApiUnprocessable(ValidationException $validator): JsonResponse
    {
        $errors       = $validator->errors();
        $messageArray = [];
        foreach ($errors as $error) {
            $messageArray[] = $error[0];
        }

        $result         = new DataResultCollection();
        $result->data   = $errors;
        $result->msg    = join('.', $messageArray);
        $result->status = false;

        return response()->json($result, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param  Request              $request
     * @param  ValidationException  $exception
     *
     * @return Application|RedirectResponse|Response|Redirector
     */
    protected function invalid($request, ValidationException $exception): ?Redirector
    {
        return redirect($exception->redirectTo ?? route('backend.login'))
            ->withInput($request->except($this->dontFlash))
            ->withErrors($exception->errors(), $exception->errorBag);
    }
}
