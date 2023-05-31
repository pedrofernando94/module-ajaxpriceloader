<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Echainr\AjaxPriceLoader\Controller\Ajax;

use Exception;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\Render as PricingRender;
use Magento\Framework\View\LayoutInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class PriceRender extends Action
{

    /**
     * @var PageFactory
     */
    protected PageFactory $_resultPageFactory;

    /**
     * @var JsonFactory
     */
    protected JsonFactory $_resultJsonFactory;

    /**
     * @var ProductRepository
     */
    protected ProductRepository $_productRepository;

    /**
     * @var PricingRender
     */
    protected PricingRender $_priceRender;

    /** @var Page|null $_resultPage */
    private ?Page $_resultPage = null;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param ProductRepository $productRepository
     * @param PricingRender $priceRender
     */
    public function __construct(
        Context           $context,
        PageFactory       $resultPageFactory,
        JsonFactory       $resultJsonFactory,
        ProductRepository $productRepository,
        PricingRender     $priceRender
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_productRepository = $productRepository;
        $this->_priceRender       = $priceRender;
    }

    /**
     * @return Json
     * @throws NoSuchEntityException
     * @throws Exception
     */
    public function execute(): Json
    {
        $response = [];
        $product_ids = $this->_request->getParam("product_ids");
        if ($product_ids) {
            $product_ids_arr = explode(",", $product_ids);
            foreach ($product_ids_arr as $product_id) {
                $product = $this->_productRepository->getById($product_id);
                $response[] = [
                    "product_id" => $product_id,
                    "price_box_html" => $this->getPriceBoxHtml(
                        $product,
                        FinalPrice::PRICE_CODE
                    )
                ];
            }
            return $this->sendJsonResponse($response);
        } else {
            throw new Exception("The GET param product_id should be set to a valid integer value.");
        }
    }

    private function getPriceBoxHtml(Product $product, string $price_code, array $arguments = []): string
    {
        $priceRenderBlock = $this->getLayout()
            ->getBlock('product.price.render.default');
        //->setData('is_product_list', true);
        return $priceRenderBlock->render(
            $price_code,
            $product,
            array_merge(
                $arguments,
                [
                    "cache_lifetime" => false,
                ]
            )
        );
    }

    /**
     * @param array $response
     * @return Json
     */
    private function sendJsonResponse(array $response): Json
    {
        $result = $this->_resultJsonFactory->create();
        $result->setData($response);
        return $result;
    }

    /**
     * @return LayoutInterface
     */
    private function getLayout(): LayoutInterface
    {
        if ($this->_resultPage === null) {
            $this->_resultPage = $this->_resultPageFactory->create();
        }
        return $this->_resultPage->getLayout();
    }
}
