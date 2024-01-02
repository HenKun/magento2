<?php
/************************************************************************
 *
 * Copyright 2023 Adobe
 * All Rights Reserved.
 *
 * NOTICE: All information contained herein is, and remains
 * the property of Adobe and its suppliers, if any. The intellectual
 * and technical concepts contained herein are proprietary to Adobe
 * and its suppliers and are protected by all applicable intellectual
 * property laws, including trade secret and copyright laws.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from Adobe.
 * ************************************************************************
 */
declare(strict_types=1);
namespace Magento\CatalogUrlRewrite\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Store\Model\Store;

class GetVisibleForStores
{

    /**
     * @param GetAttributeByStore $attributeByStore
     */
    public function __construct(
        private readonly GetAttributeByStore $attributeByStore
    ) {
    }

    /**
     * Get all store ids for which the product is visible
     *
     * @param ProductInterface $product
     * @return array
     */
    public function execute(ProductInterface $product): array
    {
        $visibilityByStore = $this->attributeByStore->execute($product, 'visibility');

        $storeIds = array_merge($product->getStoreIds(), [Store::DEFAULT_STORE_ID]) ;

        $visibleStoreIds = [];
        foreach ($storeIds as $storeId) {
            if (!isset($visibilityByStore[$storeId]) && isset($visibilityByStore[0]) &&
                (int)$visibilityByStore[0] !== Visibility::VISIBILITY_NOT_VISIBLE
                || isset($visibilityByStore[$storeId]) &&
                (int)$visibilityByStore[$storeId] !== Visibility::VISIBILITY_NOT_VISIBLE) {
                $visibleStoreIds[] = (int)$storeId;
            }
        }

        return $visibleStoreIds;
    }
}
