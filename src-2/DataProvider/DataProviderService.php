<?php

namespace LoremUserGenerator\DataProvider;

if (!defined('ABSPATH')) exit;

use LoremUserGenerator\App\Http\Response\ErrorHttpResponse;
use LoremUserGenerator\App\Http\Response\FailedHttpResponse;
use LoremUserGenerator\App\Http\Response\HttpResponse;
use LoremUserGenerator\App\Http\Response\SuccessfulHttpResponse;
use LoremUserGenerator\DataProvider\Exception\DataProviderException;
use LoremUserGenerator\LoremUserGeneratorFacade;
use Psr\Http\Client\ClientExceptionInterface;

final class DataProviderService
{
    /** @var LoremUserGeneratorFacade */
    private $app;

    public function __construct(LoremUserGeneratorFacade $app)
    {
        $this->app = $app;
    }

    public function fetchRandomUser(array $filters = []): HttpResponse
    {
        try {
            $users = $this->app->fetchUserWithRandomData($filters);
        } catch (ClientExceptionInterface $exception) {
            return new FailedHttpResponse($exception->getMessage());
        } catch (DataProviderException | \Throwable $exception) {
            $errorMessage = preg_match('/\s+time[d]?\s+out/i', $exception->getMessage())
                ? 'The request has timed out.'
                : $exception->getMessage();
            return new ErrorHttpResponse($errorMessage);
        }

        if (count($users) > 0) {
            return new SuccessfulHttpResponse($users);
        }

        return new ErrorHttpResponse('It seems no data were generated. Please, try again later.');
    }
}
