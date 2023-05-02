<?php

namespace Echainr\AjaxPriceLoader\Block;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class AjaxPriceLoader extends \Magento\Framework\View\Element\Template {

    /** @var string $_template */
    protected $_template = "Echainr_AjaxPriceLoader::ajax-price-loader.phtml";

    /** @var ProductRepository $_productRepository */
    protected ProductRepository $_productRepository;

    /** @var Product|null $_product */
    public mixed $_product = null;


    /**
     * @param Context $context
     * @param ProductRepository $productRepository
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ProductRepository $productRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_productRepository = $productRepository;
    }

    /**
     * @return Product
     * @throws NoSuchEntityException
     */
    public function getProduct(): Product
    {
        if($this->_product === null && $this->getData("product_id")) {
            $this->_product = $this->_productRepository->getById(
                $this->getData('product_id')
            );
        }
        return $this->_product;
    }

    /**
     * @param Product $product
     * @return AjaxPriceLoader
     */
    public function setProduct(Product $product): AjaxPriceLoader
    {
        $this->_product = $product;
        return $this;
    }
}
