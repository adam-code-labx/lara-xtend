<?php

namespace CodeLabX\XtendLaravel\Base;

use Composer\InstalledVersions;

class PackageInfo
{
    protected string $version = '*';

    protected array $data = [];

    protected ?string $namespace = 'Xtend\\Extensions';

    protected bool $isEnabled = false;

    public function __construct(protected string $name)
    {
        $this->setVersion();
    }

    public static function make(string $name): static
    {
        return resolve(static::class, ['name' => $name]);
    }

    public function setVersion(): static
    {
        $this->version = InstalledVersions::getVersion($this->name);

        return $this;
    }

    public function data(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function namespace(?string $namespace = null): static
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function enabled(bool $isEnabled = true): static
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'version' => $this->version,
            'data' => $this->data,
            'namespace' => $this->namespace,
            'is_enabled' => $this->isEnabled,
        ];
    }
}
