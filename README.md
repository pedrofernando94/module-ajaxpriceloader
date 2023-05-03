# Mage2 Module Echainr AjaxPriceLoader

    ``echainr/module-ajaxpriceloader``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Makes pricing load using ajax calls to hole punch full_page and block_html cache for websites that need it.

## Installation
\* = in production please use the `--keep-generated` option

### Zip file

 - Unzip the zip file in `app/code/Echainr`
 - Enable the module by running `php bin/magento module:enable Echainr_AjaxPriceLoader`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

## Configuration

Just add this to your template (.phtml) file:

```
<?php
    echo $block->getLayout()
               ->createBlock(\Echainr\AjaxPriceLoader\Block\AjaxPriceLoader::class)
               ->setData('product_id', $_product->getId())
               ->toHtml();
?>		
```

You can also do add it to a container or another block (product in parent block or registry) in your layout (.xml) file:

```
<block name="product.info.price.ajax.price.loader"
       class="Echainr\AjaxPriceLoader\Block\AjaxPriceLoader"
       template="Echainr_AjaxPriceLoader::ajax-price-loader.phtml" />
```

## Specifications

Magento >= 2.4
