<?php

namespace PhpAT\Parser;

class ClassMatcher
{
    private $namespace = '';
    private $declarations = [];

    public function saveNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    public function addDeclaration(string $declaration, ?string $alias = null)
    {
        if ($alias === null) {
            $d = explode('\\', $declaration);
            $alias = end($d);
        }

        $this->declarations[$alias] = $declaration;
    }

    public function findClass(array $parts): ?string
    {
        $link = $parts[0] ?? [];
        if (isset($this->declarations[$link])) {
            array_shift($parts);
            if (empty($parts)) {
                return $this->declarations[$link];
            }
            return $this->declarations[$link] . '\\' . implode('\\', $parts);
        }

        if (count($parts) === 1) {
            if (class_exists($parts[0]) || interface_exists($parts[0])) {
                return $parts[0];
            }

            return $this->namespace . '\\' . $parts[0];
        }

        return null;
    }

    public function reset(): void
    {
        $this->namespace = '';
        $this->declarations = [];
    }
}
