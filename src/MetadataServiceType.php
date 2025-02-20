<?php

namespace JobMetric\Metadata;

use Closure;
use Illuminate\Support\Collection;
use JobMetric\Metadata\ServiceType\Metadata;
use JobMetric\Metadata\ServiceType\MetadataBuilder;
use Throwable;

/**
 * Trait MetadataServiceType
 *
 * @package JobMetric\Metadata
 */
trait MetadataServiceType
{
    /**
     * The metadata custom fields
     *
     * @var array $metadata
     */
    protected array $metadata = [];

    /**
     * Set Metadata.
     *
     * @param Closure|array $callable
     *
     * @return static
     * @throws Throwable
     */
    public function metadata(Closure|array $callable): static
    {
        if ($callable instanceof Closure) {
            $callable($builder = new MetadataBuilder);

            $this->metadata[$this->type][] = $builder->build();
        } else {
            foreach ($callable as $metadata) {
                $builder = new MetadataBuilder;

                $builder->customField($metadata['customField'] ?? null);

                if (isset($metadata['hasFilter']) && $metadata['hasFilter'] === true) {
                    $builder->hasFilter();
                }

                $this->metadata[$this->type][] = $builder->build();
            }
        }

        $this->setTypeParam('metadata', $this->metadata);

        return $this;
    }

    /**
     * Get metadata.
     *
     * @return Collection
     */
    public function getMetadata(): Collection
    {
        $metadata = $this->getTypeParam('metadata', []);

        return collect($metadata[$this->type] ?? []);
    }
}
