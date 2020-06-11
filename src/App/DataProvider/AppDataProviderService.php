<?php

namespace LoremUserGenerator\App\DataProvider;

if (!defined('ABSPATH')) exit;

use LoremUserGenerator\App\Http\Response\HttpResponse;
use LoremUserGenerator\DataProvider\DataProviderService;
use LoremUserGenerator\Http\HttpClientService;
use LoremUserGenerator\LoremUserGeneratorFacade;

final class AppDataProviderService
{
    /** @var DataProviderService */
    private $dataProviderService;

    public function __construct()
    {
        $this->loadDataProviderService();
    }

    public function fetchRandomUser(array $filters = []): HttpResponse
    {
        return $this->dataProviderService->fetchRandomUser($filters);
    }

    private function loadDataProviderService(): void
    {
        $app = self::buildApplicationFacade();
        $this->dataProviderService = new DataProviderService($app);
    }

    private static function buildApplicationFacade(): LoremUserGeneratorFacade
    {
        $httpClient = (new HttpClientService())->getHttpClient();
        return new LoremUserGeneratorFacade($httpClient);
    }
}
