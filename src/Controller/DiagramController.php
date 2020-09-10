<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\DiagramDTO;
use App\DTO\DiagramSyncDTO;
use App\DTO\DiagramUpdateDTO;
use App\Entity\Diagram;
use App\Entity\User;
use App\Repository\DiagramRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class DiagramController.
 *
 * @Route(path="/diagram", name="api_")
 * @IsGranted("ROLE_USER")
 */
class DiagramController extends AbstractController
{
    private DiagramRepository $diagramRepo;

    private ValidatorInterface $validator;

    /**
     * DiagramController constructor.
     */
    public function __construct(DiagramRepository $diagramRepository, ValidatorInterface $validator)
    {
        $this->diagramRepo = $diagramRepository;
        $this->validator   = $validator;
    }

    /**
     * Adds a diagram with exiting id if it does not already exist. Otherwise update data.
     *
     * @Route("/sync", name="sync_diagram", methods={"POST"})
     *
     * @param DiagramSyncDTO $diagramDTO
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @return Diagram
     */
    public function syncDiagram(DiagramSyncDTO $diagramDTO): Diagram
    {
        /** @var User */
        $user    = $this->getUser();
        $diagram = $this->diagramRepo->find($diagramDTO->id);
        if ($diagram === null) {
            $diagram = $diagramDTO->toEntity($user);
        }
//        else {
//            $diagram->setData($diagramDTO->data);
//        }
        return $this->diagramRepo->persistDiagram($diagram);
    }

    /**
     * Returns the diagram by id.
     *
     * @Route("/{id}", name="get_diagram", methods={"GET"})
     */
    public function getDiagram(Diagram $diagram): Diagram
    {
        return $diagram;
    }

    /**
     * Persists a diagram to db after validating it.
     *
     * @Route("/add", name="add_diagram", methods={"POST"})
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function addDiagram(DiagramDTO $diagramDTO): Diagram
    {
        /** @var User */
        $user = $this->getUser();

        $diagramUpdate = $diagramDTO->toEntity($user);

        return $this->diagramRepo->persistDiagram($diagramUpdate);
    }

    /**
     * updates given diagram in db after validating it.
     *
     * @Route("/update/{id}", name="update_diagram", methods={"PUT"})
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateDiagram(Diagram $diagram, DiagramUpdateDTO $diagramDTO): Diagram
    {
        /** @var User */
        $user = $this->getUser();

        $this->diagramRepo->persistDiagram($diagramDTO->toEntity($diagram, $user));

        return $diagram;
    }

    /**
     * updates given diagram in db after validating it.
     *
     * @Route("/remove/{id}", name="delete_diagram", methods={"DELETE"})
     *
     * @return string[]
     */
    public function removeDiagram(string $id): array
    {
        $diagramUuid = Uuid::fromString($id);
        $diagram     = $this->diagramRepo->find($diagramUuid);
        if ($diagram !== null) {
            try {
                $this->diagramRepo->removeDiagram($diagram);

                return ['success'];
            } catch (ORMException | OptimisticLockException $e) {
                return ['Error while trying to remove diagram'];
            }
        }

        return ['Diagram could not be found'];
    }

    /**
     * Returns all Diagrams as pagination for given page number.
     *
     * @Route("/all/{page}", name="get_diagrams", methods={"GET"})
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     *
     * @return array<mixed>
     */
    public function getAll(int $page): array
    {
        $limit = 5;
        /** @var User */
        $user = $this->getUser();

        $diagrams    = $this->diagramRepo->findBy(['user' => $user->getId()], [], $limit, ($page - 1) * $limit);
        $maxDiagrams = $this->diagramRepo->getDiagramCount($user->getId()->toString());

        return ['diagrams' => $diagrams, 'max' => $maxDiagrams, 'limit' => $limit];
    }
}
