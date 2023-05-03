<?php

namespace Echainr\AjaxPriceLoader\Block;

use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class AjaxPriceLoader extends \Magento\Framework\View\Element\Template
{

    /** @var string $_template */
    protected $_template = "Echainr_AjaxPriceLoader::ajax-price-loader.phtml";

    /** @var Registry */
    protected Registry $_registry;

    /** @var int|null  */
    private ?int $product_id = null;


    /**
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_registry = $registry;
    }

    /**
     * @return int|null
     */
    public function getProductId(): ?int
    {
        if ($this->product_id === null) {
            $this->product_id = $this->getData("product_id");
            if ($this->product_id === null) {
                $product = $this->_registry->registry('product');
                if ($product instanceof SaleableInterface) {
                    $this->product_id = $product->getId();
                }
                if ($this->product_id === null) {
                    $product = $this->getParentBlock()->getProduct();
                    if ($product instanceof SaleableInterface) {
                        $this->product_id = $product->getId();
                    }
                }
            }
        }
        return $this->product_id;
    }

    /**
     * @param $product_id
     * @return void
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
    }
}
