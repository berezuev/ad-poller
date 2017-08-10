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

namespace Gtt\ADPoller\Entity;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Active directory poll task
 *
 * @author fduch <alex.medwedew@gmail.com>
 *
 * @ORM\Table(name="poll_task", options={"comment": "Poll task"})
 * @ORM\Entity(repositoryClass="Gtt\ADPoller\ORM\Repository\PollTaskRepository")
 */
class PollTask
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Name of current poller
     *
     * @var string
     *
     * @ORM\Column(name="poller_name", type="string", length=255, nullable=false,
     *     options={"comment": "Name of the poller"})
     */
    private $pollerName;

    /**
     * Invocation ID
     *
     * @var string
     *
     * @ORM\Column(name="invocation_id", type="string", length=40, nullable=false,
     *     options={"comment": "Invocation ID"})
     */
    private $invocationId;

    /**
     * Maximum value of uSNChanged attribute of the AD entities being processed during current task
     *
     * @var integer
     *
     * @ORM\Column(name="max_usnchanged_value", type="integer", nullable=false,
     *     options={"comment": "Invocation ID"})
     */
    private $maxUSNChangedValue;

    /**
     * Root DSE DNS host name used to perform the sync
     *
     * @var string
     *
     * @ORM\Column(name="root_dse_dns_host_name", type="string", length=255, nullable=false,
     *     options={"comment": "Root DSE DNS host name used to perform the sync"})
     */
    private $rootDseDnsHostName;

    /**
     * Flag holds information about full/partial sync provided by current task
     *
     * @ORM\Column(type="boolean", name="is_full_sync", options={"comment": "Full or partial sync processing"})
     *
     * @var boolean
     */
    private $isFullSync;

    /**
     * Status
     *
     * @var PollTaskStatus
     *
     * @ORM\ManyToOne(targetEntity="PollTaskStatus")
     */
    private $status;

    /**
     * Amount of entities fetched by current task
     *
     * @var integer
     *
     * @ORM\Column(name="fetched_entities_amount", type="integer", nullable=true,
     *     options={"comment": "Amount of successfully fetched entries"})
     */
    private $fetchedEntitiesAmount;

    /**
     * Error message in case of failure
     *
     * @var string
     *
     * @ORM\Column(name="error_message", type="text", nullable=true,
     *     options={"comment": "Error message in case of failure"})
     */
    private $errorMessage;

    /**
     * Created timestamp
     *
     * @var \Datetime
     *
     * @ORM\Column(type="datetime", name="created_ts", columnDefinition="TIMESTAMP NULL DEFAULT NULL")
     */
    private $created;

    /**
     * Closed timestamp
     *
     * @var \Datetime
     *
     * @ORM\Column(type="datetime", name="closed_ts", columnDefinition="TIMESTAMP NULL DEFAULT NULL")
     */
    private $closed;

    /**
     * Parent branch
     *
     * @var PollTask|null
     *
     * @ORM\ManyToOne(targetEntity="PollTask")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * PollTask constructor.
     *
     * @param string $invocationId
     * @param int    $maxUSNChangedValue
     * @param string $rootDseDnsHostName
     */
    public function __construct(
        EntityManagerInterface $em,
        $pollerName,
        $invocationId,
        $maxUSNChangedValue,
        $rootDseDnsHostName,
        PollTask $parent = null,
        $isFullSync = false)
    {
        $this->pollerName         = $pollerName;
        $this->invocationId       = $invocationId;
        $this->maxUSNChangedValue = $maxUSNChangedValue;
        $this->rootDseDnsHostName = $rootDseDnsHostName;
        $this->status             = $em->getReference(PollTaskStatus::class, PollTaskStatus::RUNNING);
        $this->parent             = $parent;
        $this->isFullSync         = $isFullSync;
        $this->created            = new DateTime();
    }

    public function succeed(EntityManagerInterface $em, $fetchedEntitiesAmount)
    {
        $this->status                = $em->getReference(PollTaskStatus::class, PollTaskStatus::SUCCEEDED);
        $this->fetchedEntitiesAmount = $fetchedEntitiesAmount;
        $this->closed                = new DateTime();
    }

    public function fail(EntityManagerInterface $em, $errorMessage)
    {
        $this->status       = $em->getReference(PollTaskStatus::class, PollTaskStatus::FAILED);
        $this->errorMessage = $errorMessage;
        $this->closed       = new DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getInvocationId()
    {
        return $this->invocationId;
    }

    /**
     * @return integer
     */
    public function getMaxUSNChangedValue()
    {
        return $this->maxUSNChangedValue;
    }

    /**
     * @return string
     */
    public function getRootDseDnsHostName()
    {
        return $this->rootDseDnsHostName;
    }
}
