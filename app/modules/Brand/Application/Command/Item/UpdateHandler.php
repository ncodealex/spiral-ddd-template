<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Brand\Application\Command\Item;

use Cycle\ORM\EntityManagerInterface;
use Modules\Brand\Application\Mapper\ItemMapper;
use Modules\Brand\Domain\Repository\ItemRepositoryInterface;
use SharedCQRS\Brand\Command\BrandPatchCommand;
use Spiral\Cqrs\Attribute\CommandHandler;

final readonly class UpdateHandler
{
    public function __construct(
        protected EntityManagerInterface  $em,
        protected ItemRepositoryInterface $repository,
    )
    {
    }


    /**
     *  Save the entity
     *
     * @param BrandPatchCommand $command
     *
     * @return bool
     */
    #[CommandHandler]
    public function __invoke(BrandPatchCommand $command): bool
    {
        // Find the entity
        $entity = $this->repository->getByPk($command->id);
        // Update the entity
        $entity = ItemMapper::updateEntity($entity, $command);
        // Save the entity
        return $this->em->persist($entity)->run()->isSuccess();

    }
}
