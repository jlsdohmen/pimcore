<?php

declare(strict_types=1);

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PCL
 */

namespace Pimcore\Bundle\EcommerceFrameworkBundle\Tracking\Tracker\Analytics;

use Pimcore\Analytics\Google\Tracker;
use Pimcore\Bundle\EcommerceFrameworkBundle\Tracking\Tracker as EcommerceTracker;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractAnalyticsTracker extends EcommerceTracker
{
    protected Tracker $tracker;

    /**
     * @internal
     *
     * TODO Pimcore 10 remove this setter and set as constructor dependency!
     */
    #[Required]
    public function setTracker(Tracker $tracker): void
    {
        $this->tracker = $tracker;
    }
}
