<?php

namespace SandFoxMe\PhpStorm\Metadata\Containers\Pimple;

use Pimple\Container;
use SandFoxMe\PhpStorm\Metadata\Common\Helpers\TypeStrings;
use SandFoxMe\PhpStorm\Metadata\Containers\ContainerIterator;

class PimpleIterator implements ContainerIterator
{
    const DEFAULT_OPTIONS = [
        'overrides' => [
            '\\Psr\\Container\\ContainerInterface::get(0)',
            'new \\Pimple\\Container',
        ],
    ];

    private $pimple;

    public function __construct(Container $pimple)
    {
        $this->pimple = $pimple;
    }

    /**
     * @return \Traversable Iterator for all container items $key => $entry
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->pimple->keys() as $key) {
            try {
                yield $key => TypeStrings::getTypeStringByInstance($this->pimple[$key]);
            } catch (\Throwable $exception) {
                yield $key => TypeStrings::getTypeStringByInstance($exception) .
                    ' /* Error message: "' . $exception->getMessage() . '" */';
            }
        }
    }
}