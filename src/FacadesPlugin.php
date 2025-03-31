<?php

/**
 * FacadesPlugin.php
 *
 * @package cake-facades
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2025 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/lagdo/cake-facades
 */

 namespace Lagdo\Cake\Facades;

use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Lagdo\Facades\ContainerWrapper;

class FacadesPlugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        // The ContainerInterface interface extends the PSR-11 ContainerInterface.
        ContainerWrapper::setContainer($container);
    }
}
