<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license	http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
if (!defined('_CAN_LOAD_FILES_'))
	exit;

class showcategories extends Module
{
	public function __construct()
	{
		$this->name = 'showcategories';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->ps_versions_compliancy = array('min' => '1.5');
		$this->author = 'David Janke';

		parent::__construct();

		$this->displayName = $this->l('Show Categories');
		$this->description = $this->l('Display categories on the homepage');
	}

	public function install()
	{
		$this->_clearCache('showcategories.tpl');
		if (Shop::isFeatureActive()) {
			Shop::setContext(Shop::CONTEXT_ALL);
		}
		return parent::install() &&
				$this->registerHook('displayHeader') &&
				$this->registerHook('displayHome');
	}

	public function uninstall()
	{
		$this->_clearCache('showcategories.tpl');
		return parent::uninstall();
	}

	public function hookDisplayHeader()
	{
	  $this->context->controller->addCSS($this->_path.'showcategories.css', 'all');
	}

	public function hookDisplayHome()
	{
		// TODO: set these options via admin configuration page
		$imageType = 'list_view';
		$showHeading = false;
		
		$id_lang = $this->context->language->id;

		$storeCategory = Category::getRootCategory();
		$categories = $storeCategory->getSubCategories($id_lang);

		$this->smarty->assign(array(
			'categories' => $categories,
			'imageType' => $imageType,
			'showHeading' => $showHeading
			));

		return $this->display(__FILE__, 'displayHome.tpl');
	}
}
