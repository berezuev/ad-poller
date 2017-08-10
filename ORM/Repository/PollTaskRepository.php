<?php
/**
 * This file is part of the Global Trading Technologies Ltd ad-poller package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * (c) fduch <alex.medwedew@gmail.com>
 *
 * Date: 11.08.17
 */

namespace Gtt\ADPoller\ORM\Repository;

use Doctrine\ORM\EntityRepository;
use Gtt\ADPoller\Entity\PollTask;
use Gtt\ADPoller\Entity\PollTaskStatus;

/**
 * PollTask repository
 *
 * @author fduch <alex.medwedew@gmail.com>
 */
class PollTaskRepository extends EntityRepository
{
    /**
     * Returns last successful finished task
     *
     * @return PollTask|null
     */
    public function findLastSuccessful()
    {
        return $this->findOneBy(['status' => PollTaskStatus::SUCCEEDED], ['closed' => 'desc']);
    }
}
