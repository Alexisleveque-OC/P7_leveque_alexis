<?php


namespace App\EventListener;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $message = sprintf(
            '{"message": "%s","code" : %s}',
            $exception->getMessage(),
            $exception->getCode()
        );
        $response = new Response();
        $response->setContent($message);
        if ($exception->getCode() != 0){
        $response->setStatusCode($exception->getCode());

        }

        $event->setResponse($response);
    }
}