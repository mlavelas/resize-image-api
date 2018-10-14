<?php

namespace Mls\CatalogImageApi\Model;

use Mls\CatalogImageApi\Api\ImageInterface;
use Magento\Catalog\Model\ProductFactory;


class ResizeImage implements ImageInterface
{
    protected $_product;
    protected $_productRepository;
    protected $appEmulation;
    protected $storeManager;
    protected $helperFactory;

    public function __construct(
        ProductFactory $product,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Store\Model\App\Emulation $appEmulation,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Helper\ImageFactory $helperFactory
    )
    {
        $this->_productRepository = $productRepository;
        $this->_product = $product;
        $this->_appEmulation = $appEmulation;
        $this->_storeManager = $storeManager;
        $this->_helperFactory = $helperFactory;
    }

    public function resizeImage($sku, $width, $height = null, $image_type = 'product_base_image') {
        $storeId = $this->_storeManager->getStore()->getId();
        $this->_appEmulation->startEnvironmentEmulation($storeId, \Magento\Framework\App\Area::AREA_FRONTEND, true);

        $productId = $this->_product->create()->getIdBySku($sku);
        $product = $this->_productRepository->getById($productId);

        $resizedUrl = $this->_helperFactory->create()->init($product, $image_type)
            ->keepAspectRatio(TRUE)
            ->constrainOnly(TRUE)
            ->keepFrame(TRUE)
            ->resize($width, $height)
            ->getUrl();

        $this->_appEmulation->stopEnvironmentEmulation();

        return $resizedUrl;
    }
}
