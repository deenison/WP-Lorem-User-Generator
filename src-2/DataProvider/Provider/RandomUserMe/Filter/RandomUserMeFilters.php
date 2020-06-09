<?php

namespace LoremUserGenerator\DataProvider\Provider\RandomUserMe\Filter;

if (!defined('ABSPATH')) exit;

final class RandomUserMeFilters
{
    private const SUPPORTED_NATIONALITIES = [];

    /** @var int */
    private $results;

    /** @var string|null */
    private $gender;

    /** @var string[] */
    private $nationalities;

    private function __construct()
    {
    }

    public static function builder(): RandomUserMeFiltersBuilder
    {
        $instance = new self();
        $attributeSetter = function (string $name, $value) use ($instance) {
            $instance->{$name} = $value;
        };

        return new RandomUserMeFiltersBuilder($instance, $attributeSetter);
    }

    public static function fromArray(array $options): self {
        $filtersEntityBuilder = RandomUserMeFilters::builder();

        $results = $options['results'] ?? 0;
        if (is_numeric($results) && $results > 1) {
            $filtersEntityBuilder->withResults($results);
        }

        $gender = $options['gender'] ?? '';
        if (!empty($gender)) {
            $filtersEntityBuilder->withGender($gender);
        }

        $nationalities = array_filter(
            $options['nationalities'] ?? [],
            function ($countryAbbr) {
                return in_array(strtoupper($countryAbbr), self::SUPPORTED_NATIONALITIES);
            }
        );

        if (!empty($nationalities)) {
            $filtersEntityBuilder->withNationalities($nationalities);
        }

        return $filtersEntityBuilder->build();
    }

    public function getResults(): int
    {
        return $this->results ?? 1;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getNationalities(): array
    {
        return $this->nationalities ?? [];
    }
}
