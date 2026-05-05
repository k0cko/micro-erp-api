<?php

namespace App\Service\Product;

use App\Entity\Product;
use App\Exception\ResourceInUseException;
use Doctrine\ORM\EntityManagerInterface;

final class DeleteProductService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManagerInterface
    ) {}

    public function execute(Product $product): void
    {
        if (!$product->getProductMovements()->isEmpty()) {
            throw ResourceInUseException::forEntity('Product', 'ProductMovement');
        }
    
        $this->entityManagerInterface->remove($product);
        $this->entityManagerInterface->flush();
    }
}