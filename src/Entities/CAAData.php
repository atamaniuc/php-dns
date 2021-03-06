<?php

namespace RemotelyLiving\PHPDNS\Entities;

use RemotelyLiving\PHPDNS\Exceptions;

class CAAData extends DataAbstract
{
    /**
     * @var int
     */
    private $flags;

    /**
     * @var string
     */
    private $tag;

    /**
     * @var string|null
     */
    private $value;

    public function __construct(int $flags, string $tag, string $value = null)
    {
        $this->flags = $flags;
        $this->tag = $tag;
        $this->value = ($value)
            ? $this->normalizeValue($value)
            : null;
    }

    public function __toString(): string
    {
        return "{$this->flags} {$this->tag} \"{$this->value}\"";
    }

    public function getFlags(): int
    {
        return $this->flags;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function toArray(): array
    {
        return [
            'flags' => $this->flags,
            'tag' => $this->tag,
            'value' => $this->value,
        ];
    }

    public function serialize(): string
    {
        return \serialize($this->toArray());
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized): void
    {
        $unserialized = \unserialize($serialized);
        $this->flags = $unserialized['flags'];
        $this->tag = $unserialized['tag'];
        $this->value = $unserialized['value'];
    }

    private function normalizeValue(string $value): string
    {
        $normalized = trim(str_ireplace('"', '', $value));

        if (preg_match('/\s/m', $normalized)) {
            throw new Exceptions\InvalidArgumentException("$value is not a valid CAA value");
        }

        return $normalized;
    }
}
