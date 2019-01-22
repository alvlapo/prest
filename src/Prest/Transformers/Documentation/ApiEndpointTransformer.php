<?php

namespace Prest\Transformers\Documentation;

use Prest\Export\Documentation\ApiEndpoint as DocumentationEndpoint;
use Prest\Transformers\Transformer;

class ApiEndpointTransformer extends Transformer
{
    public function transform(DocumentationEndpoint $endpoint)
    {
        return [
            'name' => $endpoint->getName(),
            'description' => $endpoint->getDescription(),
            'httpMethod' => $endpoint->getHttpMethod(),
            'path' => $endpoint->getPath(),
            'exampleResponse' => $endpoint->getExampleResponse(),
            'allowedRoles' => $endpoint->getAllowedRoles()
        ];
    }
}
