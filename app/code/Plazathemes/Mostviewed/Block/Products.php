<?php
/**
* Copyright © 2015 PlazaThemes.com. All rights reserved.

* @author PlazaThemes Team <contact@plazathemes.com>
*/

namespace Plazathemes\Mostviewed\Block;

class Products extends \Magento\Framework\View\Element\Template {

	protected $_scopeConfig;

	/**
	 * @var \Magento\Store\Model\StoreManagerInterface
	 */
	protected $_storeManager;

	/**
	 * [__construct description]
	 * @param \Magento\Framework\View\Element\Template\Context                $context                 [description]
	 * @param \Magento\Framework\Registry                                     $coreRegistry            [description]
	 * @param array                                                           $data                    [description]
	 */
	public function __construct(
		\Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\App\Http\Context $httpContext,
		\Magento\Catalog\Block\Product\ListProduct $block,
		\Magento\Reports\Model\ResourceModel\Product\CollectionFactory $reportCollection,
		\Magento\Catalog\Model\Product\Visibility $productVisibility,
        array $data = []
	) {
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->httpContext = $httpContext;
        $this->block = $block;
		$this->_reportCollection = $reportCollection;
		$this->productVisibility = $productVisibility;
        parent::__construct(
            $context,
            $data
        );
	}
	
	public function getBlockProduct()
	{
		return $this->block;
	}
	
	public function getProducts(){
		$products = $this->_reportCollection->create()->addAttributeToSelect('*')->addViewsCount();
		$products
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addUrlRewrite()
            ->setVisibility($this->productVisibility->getVisibleInCatalogIds())
			->addStoreFilter();
		$products->getSelect()->group("e.entity_id");
        $products->setPageSize($this->getConfig('qty'))->setCurPage(1);
		return $products;
   }
   
	public function getConfig($config)
	{
		return $this->_scopeConfig->getValue('mostviewed/general/'.$config, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

	/**
	 * Add elements in layout
	 *
	 * @return
	 */
	protected function _prepareLayout() {
		return parent::_prepareLayout();
	}
}
