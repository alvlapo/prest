<?php

namespace Prest\Transformers\Documentation;

use Prest\Export\Documentation\ApiCollection as DocumentationCollection;
use Prest\Transformers\Transformer;

class ApiCollectionTransformer extends Transformer
{
    public $defaultIncludes = [
        'endpoints'
    ];

    public function transform(DocumentationCollection $collection)
    {
        return [
            'name' => $collection->getName(),
            'description' => $collection->getDescription(),
            'prefix' => $collection->getPath(),
            'fields' => $collection->getFields()
        ];
    }

    public function includeEndpoints(DocumentationCollection $collection)
    {
        return $this->collection($collection->getEndpoints(), new ApiEndpointTransformer);
    }
}
