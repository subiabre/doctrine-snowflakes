<?php

namespace Subiabre;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Godruoyi\Snowflake\FileLockResolver;
use Godruoyi\Snowflake\Snowflake;
use Godruoyi\Snowflake\Sonyflake;

/**
 * Generate numeric, time based IDs that are ideal for distributed environments
 */
class SnowflakeGenerator extends AbstractIdGenerator
{
    /**
     * @var int UNIX date of start for timestamps
     */
    public readonly int $startdate;
    
    /**
     * @var int Number of the datacenter
     */
    public readonly int $datacenter;

    /**
     * @var int Number of the worker machine
     */
    public readonly int $worker;

    /**
     * @var string Path to the lock directory for the sequence resolver
     */
    public readonly string $lockdir;

    /**
     * @var Snowflake
     */
    private Snowflake $snowflake;

    /**
     * @param int $startdate UNIX timestamp, by default its 2000-01-01
     * @param int $datacenter ID of the datacenter
     * @param int $worker ID of the worker machine
     * @param string $lockdir Path to the directory where lock files will be stored
     * @throws \Exception If the $lockdir path does not exist or is not a directory
     */
    public function __construct(
        int $startdate = 946681200000,
        int $datacenter = 0,
        int $worker = 0,
        ?string $lockdir = null
    ) {
        $lockFileDir = $lockdir ?? sprintf('%s%s%s', dirname(__DIR__), DIRECTORY_SEPARATOR, 'lock');

        if (!\file_exists($lockFileDir)) {
            throw new \Exception("Could not find path to lockdir at $lockdir");
        }

        if (!\is_dir($lockFileDir)) {
            throw new \Exception("Path to lockdir $lockdir is not a directory", 1);
        }

        $this->snowflake = new Sonyflake(intval(sprintf('%d%d', $datacenter, $worker)));

        $this->snowflake->setStartTimeStamp($startdate);
        $this->snowflake->setSequenceResolver(new FileLockResolver($lockFileDir));

        $this->startdate  = $startdate;
        $this->datacenter = $datacenter;
        $this->worker     = $worker;
        $this->lockdir    = $lockFileDir;
    }

    public function new(): string
    {
        return $this->snowflake->id();
    }

    public function generateId(EntityManagerInterface $em, $entity)
    {
        return $this->snowflake->id();
    }
}
