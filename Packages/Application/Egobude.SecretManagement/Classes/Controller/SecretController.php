<?php
namespace Egobude\SecretManagement\Controller;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;

/**
 * SecretController
 */
class SecretController extends ActionController
{

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->assign('foos', array(
            'bar', 'baz'
        ));
    }

}
