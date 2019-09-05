<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\Request;
use App\Exceptions\LaravelBaseApiException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

trait RestExceptionHandlerTrait
{
    /**
     * Creates a new JSON response based on exception type.
     *
     * @param Request $request
     * @param Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getJsonResponseForException(Exception $e)
    {
        if (config('APP_ENV') == 'local') {
            Log::error($e->getMessage());
        }

        switch (true) {
            case $this->isAuthenticationException($e):
                $retval = $this->unauthorized();
                break;
            case $this->isModelNotFoundException($e):
                $retval = $this->modelNotFound();
                break;
            case $this->isMethodNotAllowedHttpException($e):
                $retval = $this->methodNotFound();
                break;
            case $this->isLaravelBaseApiException($e):
                $retval = $this->handleLaravelBaseApiException($e);
                break;
            case $this->isAuthorizationException($e):
                $retval = $this->authorization();
                break;
            default:
                $retval = $this->badRequest();
        }

        return $retval;
    }

    /**
     * Returns json response for generic bad request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function badRequest()
    {
        return $this->jsonResponse([
            'code' => config('api_exception.bad_request.error_code'),
            'message' => config('api_exception.bad_request.message'),
        ]);
    }

    /**
     * Returns json response for unauthorized exception.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function unauthorized()
    {
        return $this->jsonResponse([
            'code' => config('api_exception.unauthorized.error_code'),
            'message' => config('api_exception.unauthorized.message'),
        ]);
    }

    /**
     * Returns json response for eloquent model not found exception.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function modelNotFound()
    {
        return $this->jsonResponse([
            'code' => config('api_exception.model_not_found.error_code'),
            'message' => config('api_exception.model_not_found.message'),
        ]);
    }

    /**
     * Returns json response for method not found exception.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function methodNotFound()
    {
        return $this->jsonResponse([
            'code' => config('api_exception.method_not_found.error_code'),
            'message' => config('api_exception.method_not_found.message'),
        ]);
    }

    /**
     * Returns json response for method authorization exception.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function authorization()
    {
        return $this->jsonResponse([
            'code' => config('api_exception.fail_authorization.error_code'),
            'message' => config('api_exception.fail_authorization.message'),
        ]);
    }

    /**
     * Returns json response for LaravelBaseApiException.
     *
     * @param LaravelBaseApiException $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleLaravelBaseApiException(LaravelBaseApiException $exception)
    {
        return $this->jsonResponse([
            'code' => $exception->getErrorCode(),
            'message' => $exception->getErrorMessage(),
        ]);
    }

    /**
     * Returns json response.
     *
     * @param array|null $payload
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse(array $payload = null, $statusCode = 400)
    {
        $response = [
            'success' => false,
            'error' => $payload,
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Determines if the given exception is an Eloquent model not found.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isModelNotFoundException(Exception $e)
    {
        return $e instanceof ModelNotFoundException;
    }

    /**
     * Determines if the given exception is wrong router method.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isMethodNotAllowedHttpException(Exception $e)
    {
        return $e instanceof MethodNotAllowedHttpException;
    }

    /**
     * Determines if the given exception is unauthentication.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isAuthenticationException(Exception $e)
    {
        return $e instanceof AuthenticationException;
    }

    /**
     * Determines if the given exception is LaravelBaseApiException.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isLaravelBaseApiException(Exception $e)
    {
        return $e instanceof LaravelBaseApiException;
    }

    /**
     * Determines if the given exception is AuthorizationException.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isAuthorizationException(Exception $e)
    {
        return $e instanceof AuthorizationException;
    }
}
