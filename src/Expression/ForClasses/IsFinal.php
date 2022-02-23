<?php
declare(strict_types=1);

namespace Arkitect\Expression\ForClasses;

use Arkitect\Analyzer\ClassDescription;
use Arkitect\Expression\Description;
use Arkitect\Expression\Expression;
use Arkitect\Expression\PositiveDescription;
use Arkitect\Rules\Violation;
use Arkitect\Rules\Violations;

class IsFinal implements Expression
{
    public function describe(ClassDescription $theClass): Description
    {
        return new PositiveDescription("{$theClass->getName()} should be final");
    }

    public function evaluate(ClassDescription $theClass, Violations $violations): void
    {
        if ($theClass->isFinal()) {
            return;
        }

        $violation = Violation::create(
            $theClass->getFQCN(),
            $this->describe($theClass)->toString()
        );

        $violations->add($violation);
    }
}
