<?php

namespace Mls\CatalogImageApi\Api;

interface ImageInterface

{

    /**
     * Returns catalog product image with specified size
     *
     * @api
     * @param string $sku
     * @param int $width
     * @param int $height
     * @param string $image_type
     * @return $this
     */

    public function resizeImage($sku, $width, $height = null, $image_type = 'product_base_image');
}
