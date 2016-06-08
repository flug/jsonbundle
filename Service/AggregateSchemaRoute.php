<?php


namespace Piktalent\Bundle\JsonLDBundle\Service;


class AggregateSchemaRoute
{

    private $schemas = [];

    public function __construct(array $schemas = [])
    {
        $this->schemas = $schemas;
    }


    public function findSchemaByRouteKey($key)
    {
        $routes = $this->schemas['routes'];
        foreach ($routes as $routeKey => $schema) {
            if ($routeKey == $key) {

                if (self::isSchemaValid($schema)) {
                    $schema['json'] = $this->reformatSchema($schema['json']);
                    return $schema;
                } else {
                    return null;
                }
            }
        }
        return null;
    }

    public static function isSchemaValid(array $schema = [])
    {
        return array_key_exists('json', $schema);
    }


    private function reformatSchema($schema)
    {
        foreach ($schema as $keySchema => $item) {

            if (is_array($item)) {
                foreach ($item as $key => $value) {
                    if (is_array($value)) {
                        $schema[$key] = $this->reformatSchema($value);
                    } else {
                        $schema[$key] = $value;
                    }
                    unset($schema[$keySchema]);
                }
            }
        }
        return $schema;
    }
}