<?php

namespace LoremUserGenerator\DataProvider\Provider\RandomUserMe\Filter;

if (!defined('ABSPATH')) exit;

final class RandomUserMeFiltersBuilder
{
    /** @var RandomUserMeFilters */
    private $instance;
    /** @var callable */
    private $attributeSetter;

    /** @var int */
    private $results;

    /** @var string */
    private $gender;

    /** @var string[] */
    private $nationalities;

    public function __construct(RandomUserMeFilters $instance, callable $attributeSetter)
    {
        $this->instance = $instance;
        $this->attributeSetter = $attributeSetter;
    }

    public function withResults(int $results): self
    {
        $this->results = $results;
        return $this;
    }

    public function withGender(string $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    public function withNationalities(array $nationalities): self
    {
        $this->nationalities = $nationalities;
        return $this;
    }

    public function build(): RandomUserMeFilters
    {
        $this->setProperty('results', $this->results);
        $this->setProperty('gender', $this->gender);
        $this->setProperty('nationalities', $this->nationalities);

        unset($this->attributeSetter);

        return $this->instance;
    }

    private function setProperty(string $attributeName, $attributeValue): void
    {
        if (!isset($this->attributeSetter)) {
            throw new \LogicException('An instance cannot be changed after being built');
        }

        $attributeSetter = $this->attributeSetter;
        $attributeSetter($attributeName, $attributeValue);
    }
}
