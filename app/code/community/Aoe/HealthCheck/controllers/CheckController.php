<?php
/**
 * Check controller
 *
 * @author Fabrizio Branca
 */
class Aoe_HealthCheck_CheckController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {
        $this->getResponse()->setBody('OK');
    }
}
