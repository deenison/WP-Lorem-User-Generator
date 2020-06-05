<?php

namespace LoremUserGenerator\App\Modules\AddMultipleUsers\Http\Request\FetchUsers;

final class FetchUsersRequestPayload
{
    /** @var int */
    private $results;
    /** @var string */
    private $gender;
    /** @var string[] */
    private $nationalities;

    private function __construct(
        int $resultsQuantity,
        string $gender = '',
        array $nationalities = []
    ) {
        $this->results = $resultsQuantity;
        $this->gender = $gender;
        $this->nationalities = $nationalities;
    }

    public function getResultsQuantity(): int
    {
        return $this->results;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getNationalities(): array
    {
        return $this->nationalities;
    }

    public function toArray(): array
    {
        return [
            'results' => $this->results,
            'gender' => $this->gender,
            'nationalities' => $this->nationalities,
        ];
    }

    public static function fromRequest(): self
    {
        $resultsQuantity = self::retrieveResultsFromRequest();
        $gender = self::retrieveGenderFromRequest();
        $nationalities = self::retrieveNationalitiesFromRequest();

        return new self(
            $resultsQuantity,
            $gender,
            $nationalities
        );
    }

    private static function retrieveResultsFromRequest(): int
    {
        $unsanitizedResultsValue = $_GET['qty'] ?? '';
        $sanitizedResultsValue = (string)filter_var($unsanitizedResultsValue, FILTER_SANITIZE_NUMBER_INT);

        if (
            empty($sanitizedResultsValue)
            || !is_numeric($sanitizedResultsValue)
            || strlen($sanitizedResultsValue) > 2
            || (int)$sanitizedResultsValue < 0
            || (int)$sanitizedResultsValue > 25
        ) {
            return 1;
        }

        return (int)$sanitizedResultsValue;
    }

    private static function retrieveGenderFromRequest(): string
    {
        $unsanitizedGenderValue = $_GET['gender'] ?? '';

        $sanitizedGenderValue = filter_var($unsanitizedGenderValue, FILTER_SANITIZE_STRING);
        if (empty($sanitizedGenderValue) || !is_string($sanitizedGenderValue)) {
            return '';
        }

        $normalizedGenderValue = strtolower($sanitizedGenderValue);
        return in_array($normalizedGenderValue, ['female', 'male'])
            ? $normalizedGenderValue
            : '';
    }

    private static function retrieveNationalitiesFromRequest(): array
    {
        $unsanitizedNationalities = $_GET['nationalities'] ?? [];
        if (!is_array($unsanitizedNationalities) || empty($unsanitizedNationalities)) {
            return [];
        }

        return array_filter(
            $unsanitizedNationalities,
            function ($nationality): bool {
                $nationality = filter_var($nationality, FILTER_SANITIZE_STRING);
                return is_string($nationality) && strlen($nationality) > 0;
            }
        );
    }
}
