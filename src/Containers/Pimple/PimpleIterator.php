<?php

namespace SandFox\PhpStorm\Metadata\Containers\Pimple;

use Pimple\Container;
use SandFox\PhpStorm\Metadata\Common\Helpers\TypeStrings;
use SandFox\PhpStorm\Metadata\Containers\ContainerIterator;

/**
 * @internal
 */
class PimpleIterator implements ContainerIterator
{
    private $pimple;

    public static function getDefaultOptions(): array
    {
        return [
            'overrides' => [
                '\\Psr\\Container\\ContainerInterface::get(0)',
                'new \\Pimple\\Container',
            ],
        ];
    }

    public function __construct(Container $pimple)
    {
        $this->pimple = $pimple;
    }

    /**
     * @return \Traversable Iterator for all container items $key => $entry
     */
    public function getIterator(): \Traversable
    {
        $keys = $this->pimple->keys();

        sort($keys);

        foreach ($keys as $key) {
            try {
                yield $key => TypeStrings::getTypeStringByInstance($this->pimple[$key]);
            } catch (\Throwable $exception) {
                yield $key => TypeStrings::getTypeStringByInstance($exception) .
                    ' /* Error message: "' . $exception->getMessage() . '" */';
            }
        }
    }
}
