<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Customer
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Customer account controller
 *
 * @category   Mage
 * @package    Mage_Customer
 * @author      Magento Core Team <core@magentocommerce.com>
 */
require_once(Mage::getModuleDir('controllers','Mage_Customer').DS.'AccountController.php');
class Chinese_Customer_AccountController extends Mage_Customer_AccountController
{
   /**
     * Customer logout action, with overloading logout action to make it redirected to home page instead
     */
    public function logoutAction()
    {
         # Just to make sure
        //error_log('Yes, I did it!');
        //parent::logoutAction();
    
    	$this->_getSession()->logout()
            ->renewSession()
            ->setBeforeAuthUrl($this->_getRefererUrl());
        //Redirect to home page
        $this->_redirect();
        
    }
    
    /**
     * Add welcome message and send new account email.
     * Returns success URL
     *
     * @param Mage_Customer_Model_Customer $customer
     * @param bool $isJustConfirmed
     * @return string
     */
    protected function _welcomeCustomer(Mage_Customer_Model_Customer $customer, $isJustConfirmed = false)
    {
    	$this->_getSession()->addSuccess(
    			$this->__('Thank you for registering with %s.', Mage::app()->getStore()->getFrontendName())
    	);
    	if ($this->_isVatValidationEnabled()) {
    		// Show corresponding VAT message to customer
    		$configAddressType =  $this->_getHelper('customer/address')->getTaxCalculationAddressType();
    		$userPrompt = '';
    		switch ($configAddressType) {
    			case Mage_Customer_Model_Address_Abstract::TYPE_SHIPPING:
    				$userPrompt = $this->__('If you are a registered VAT customer, please click <a href="%s">here</a> to enter you shipping address for proper VAT calculation',
    				$this->_getUrl('customer/address/edit'));
    				break;
    			default:
    				$userPrompt = $this->__('If you are a registered VAT customer, please click <a href="%s">here</a> to enter you billing address for proper VAT calculation',
    				$this->_getUrl('customer/address/edit'));
    		}
    		$this->_getSession()->addSuccess($userPrompt);
    	}
    
    	$customer->sendNewAccountEmail(
    			$isJustConfirmed ? 'confirmed' : 'registered',
    			'',
    			Mage::app()->getStore()->getId()
    	);
    
    	$successUrl = $this->_getUrl('learnlan/beginner.html', array('_secure' => true));
    	if ($this->_getSession()->getBeforeAuthUrl()) {
    		$successUrl = $this->_getSession()->getBeforeAuthUrl(true);
    	}
    	return $successUrl;
    }
	
}
