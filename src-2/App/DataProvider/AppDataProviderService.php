<?php

namespace LoremUserGenerator\App\DataProvider;

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

    public function fetchRandomUser(): HttpResponse
    {
        return $this->dataProviderService->fetchRandomUser();
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
