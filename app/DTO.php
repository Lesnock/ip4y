<?php

namespace App;

use App\Utils\StringUtils;
use ReflectionObject;
use ReflectionProperty;

/**
 * DTO
 * 
 * Esta é a classe base que deve ser extendida por todos os DTO's
 * Os "Data Transfer Object" são responsáveis por trafegar os dados entre as camadas, como por exemplo
 * entre a camada de persistência (banco de dados) e a camada de domínio (regras de negócio), como também
 * entre a camada de domínio e camada de exibição (frontend).
 */
abstract class DTO
{
    /**
     * Constructor
     * Preenchemos as propriedades automaticamente transformando snake_case em camelCase
     * @param array $data
     */
    public function __construct(array $data)
    {
        $publicProps = (new ReflectionObject($this))
            ->getProperties(ReflectionProperty::IS_PUBLIC);

        $allowedProps = array_map(fn ($prop) => $prop->getName(), $publicProps);

        foreach ($data as $name => $value) {
            $camelCaseName = StringUtils::snakeCaseToCamelCase($name);
            if (in_array($camelCaseName, $allowedProps)) {
                $this->$camelCaseName = $value;
            }
        }
    }

    /**
     * Build
     * Sempre transformamos os dados de camelCase para snake_case ao transformar em Array
     * @param array $data
     * @return array
     */
    public function toArray(): array
    {
        $props = get_object_vars($this);

        $array = [];
        foreach ($props as $name => $value) {
            $snakeCaseName = StringUtils::camelCaseToSnakeCase($name);
            $array[$snakeCaseName] = $value;
        }

        return $array;
    } 
}