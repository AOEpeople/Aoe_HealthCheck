<?php
/**
 * Class Aoe_HealthCheck_Model_Observer
 *
 * @author Fabrizio Branca
 * @since 2014-10-16
 */
class Aoe_HealthCheck_Model_Observer {

    /**
     * Little hack to make sure web/url/redirect_to_base is set to false for this controller in any case.
     * Otherwise Magento would redirect to the base url which would result into an 302 status code instead of a 200 which in turn
     * wouldn't be detected as a healthy instance.
     *
     * Check Mage_Core_Controller_Varien_Front->_checkBaseUrl()
     *
     * This method is called in event 'controller_front_init_routers' which is the closest event to _checkBaseUrl
     */
    public function disableRedirectToBase() {
        $originalPathInfo = Mage::app()->getRequest()->getOriginalPathInfo();
        if (strpos($originalPathInfo, '/health/check') === 0) {
            $redirectCode = (int)Mage::getStoreConfig('web/url/redirect_to_base');
            if ($redirectCode) {
                Mage::app()->getStore(null)->setConfig('web/url/redirect_to_base', false);
            }

            // check Mage_Core_Controller_Varien_Router_Standard->_shouldBeSecure()
            $baseUrl = Mage::getStoreConfig('web/unsecure/base_url');
            if (substr($baseUrl, 0, 5) === 'https') {
                $baseUrl = 'http' . substr($baseUrl, 5);
                Mage::app()->getStore(null)->setConfig('web/unsecure/base_url', $baseUrl);
            }
            if (Mage::getStoreConfigFlag('web/secure/use_in_frontend')) {
                Mage::app()->getStore(null)->setConfig('web/secure/use_in_frontend', false);
            }
        }
    }

}