<?php

namespace LoremUserGenerator\DataProvider\Provider\RandomUserMe\Filter;

final class RandomUserMeFilters
{
    /** @var int */
    private $results;

    /** @var string|null */
    private $gender;

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

    public function getResults(): int
    {
        return $this->results ?? 1;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }
}
