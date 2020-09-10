<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Diagram;

/**
 * Interface DiagramRepositoryInterface.
 */
interface DiagramRepositoryInterface
{
    /**
     * persist a diagram to the db.
     */
    public function persistDiagram(Diagram $diagram): Diagram;

    /**
     * Return the count of all diagrams in the db.
     */
    public function getDiagramCount(string $userId): int;
}
