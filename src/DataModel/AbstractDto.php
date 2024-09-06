<?php

namespace Tfc\DataModel;

use RuntimeException;

abstract class AbstractDto
{
    static public function fromArray(array $datum, bool $ignoreMissed = true): self
    {
        $properties = get_class_vars(static::class);

        $propIntersecction = array_intersect(
            array_keys($properties),
            array_keys($datum),
        );
        
        if (!$ignoreMissed && count($propIntersecction) != count($properties)) {
            $missedProperties = array_diff(
                array_keys($properties),
                $propIntersecction,
            );

            throw new RuntimeException(sprintf(
                'Missed properties: [%s].',
                implode( ', ', $missedProperties),
            ));
        }

        $model = new static();

        foreach ($propIntersecction as $propertyKey) {
            $method = sprintf('set%s', ucfirst($propertyKey));
            
            if (method_exists($model, $method)) {
                call_user_func_array([$model, $method], [$datum[$propertyKey]]);
            }
        }

        return $model;
    }
}