<?php

/*
 * This file is part of the Symfony2-Boilerplate repository.
 *
 * (c) Diego Caponera <http://moonwave99.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MWLabs\FrontendBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FrontendBundle extends Bundle
{

    public function getParent()
    {

        return 'KnpPaginatorBundle';

    }

}