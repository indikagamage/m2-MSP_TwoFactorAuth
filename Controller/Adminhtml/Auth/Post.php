<?php
/**
 * IDEALIAGroup srl
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@idealiagroup.com so we can send you a copy immediately.
 *
 * @category   MSP
 * @package    MSP_TwoFactorAuth
 * @copyright  Copyright (c) 2016 IDEALIAGroup srl (http://www.idealiagroup.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace MSP\TwoFactorAuth\Controller\Adminhtml\Auth;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use MSP\TwoFactorAuth\Api\TfaInterface;

class Post extends Action
{
    protected $tfaInterface;

    public function __construct(
        Context $context,
        TfaInterface $tfaInterface
    ) {
        $this->tfaInterface = $tfaInterface;

        parent::__construct($context);
    }

    public function execute()
    {
        $token = $this->getRequest()->getParam('tfa_code');
        
        if ($this->tfaInterface->verify($token)) {
            $this->tfaInterface->setTwoAuthFactorPassed(true);

            return $this->_redirect('/');
        } else {
            $this->messageManager->addError('Invalid code');
            return $this->_redirect('msp_twofactorauth/auth/index');
        }
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
}
