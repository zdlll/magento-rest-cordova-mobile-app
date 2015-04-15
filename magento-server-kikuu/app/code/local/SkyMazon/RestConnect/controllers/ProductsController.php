<?php
class SkyMazon_RestConnect_ProductsController extends Mage_Core_Controller_Front_Action {
	public function getcustomoptionAction() {
		$productid = $this->getRequest ()->getParam ( 'productid' );
		$product = Mage::getModel ( "catalog/product" )->load ( $productid );
		$selectid = 1;
		$select = array ();
		foreach ( $product->getOptions () as $o ) {
			$optionid = 1;
			//custom_option_type两种类型没有取到value值，field和 file
			$values = $o->getValues ();
			$options = array ();
			foreach ( $values as $v ) {                
				$options [$optionid] = $v->getData ();
				$optionid ++;
			}
			if ($o->getType () == "field")	{
				$max_characters = $o->getMaxCharacters ();
				$price = $o->getFormatedPrice ();		//id为535的产品，没有值输出，应该输出 555的美金再转换的值
				}
			$select [$selectid] = array (
					'option_id' => $o->getId (),
					'is_require' => $o->getIsRequire (),
					'price' => $price,
					'max_characters' => $max_characters,
					'custom_option_type' => $o->getType (),
					'custom_option_title' => $o->getTitle (),
					'custom_option_value' => $options 
			);
			$selectid ++;
			// echo "----------------------------------<br/>";
		}
		echo json_encode ( $select );
	}
	public function getproductdetailAction() {
		$productdetail = array ();
		$baseCurrency = Mage::app ()->getStore ()->getBaseCurrency ()->getCode ();
		$currentCurrency = Mage::app ()->getStore ()->getCurrentCurrencyCode ();
		$productid = $this->getRequest ()->getParam ( 'productid' );
		$product = Mage::getModel ( "catalog/product" )->load ( $productid );		
		$productdetail  = array (
				'entity_id' => $product->getId (),
				'sku' => $product->getSku (),
				'name' => $product->getName (),
				'news_from_date' => $product->getNewsFromDate (),
				'news_to_date' => $product->getNewsToDate (),
				'special_from_date' => $product->getSpecialFromDate (),
				'special_to_date' => $product->getSpecialToDate (),
				'image_url' => $product->getImageUrl (),
				'url_key' => $product->getProductUrl (),
				'regular_price_with_tax' => number_format ( Mage::helper ( 'directory' )->currencyConvert ( $product->getPrice (), $baseCurrency, $currentCurrency ), 2, '.', '' ),
				'final_price_with_tax' => number_format ( Mage::helper ( 'directory' )->currencyConvert ( $product->getSpecialPrice (), $baseCurrency, $currentCurrency ), 2, '.', '' ),
				'description'=>nl2br($product->getDescription()),
				'symbol' => Mage::app ()->getLocale ()->currency ( Mage::app ()->getStore ()->getCurrentCurrencyCode () )->getSymbol ()
		);
		echo json_encode ( $productdetail );
	}
} 