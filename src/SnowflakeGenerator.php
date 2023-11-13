<?php

namespace Subiabre;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Godruoyi\Snowflake\FileLockResolver;
use Godruoyi\Snowflake\SequenceResolver;
use Godruoyi\Snowflake\Snowflake;
use Godruoyi\Snowflake\Sonyflake;

/**
 * Generate numeric, time based ids that are ideal for distributed environments
 */
class SnowflakeGenerator extends AbstractIdGenerator
{
    /**
     * @var int Number of the datacenter
     */
    public readonly int $datacenter;

    /**
     * @var int Number of the worker machine
     */
    public readonly int $worker;

    /**
     * @var int UNIX date of start for timestamps
     */
    public readonly int $stardate;

    /**
     * @var string Path to the lock directory for the sequence resolver
     */
    public readonly string $lockdir;

    /**
     * @var SequenceResolver
     */
    private SequenceResolver $resolver;

    /**
     * @var Snowflake
     */
    private Snowflake $generator;

    /**
     * @param int $startdate UNIX timestamp, by default its 2000-01-01
     * @param int $datacenter ID of the datacenter
     * @param int $worker ID of the worker machine
     */
    public function __construct(
        int $startdate = 946681200000,
        int $datacenter = 0,
        int $worker = 0
    ) {;
        $this->stardate   = $startdate;
        $this->datacenter = $datacenter;
        $this->worker     = $worker;
        $this->lockdir    = sprintf('%s%s%s', dirname(__DIR__), DIRECTORY_SEPARATOR, 'lock');

        $this->resolver  = $this->buildResolver();
        $this->generator = $this->buildGenerator();
    }

    public function new()
    {
        return $this->generator->id();
    }

    public function generateId(EntityManagerInterface $em, $entity)
    {
        return $this->generator->id();
    }

    /**
     * Build the resolver using the RandomSequenceResolver
     */
    private function buildResolver(): SequenceResolver
    {
        return new FileLockResolver($this->lockdir);
    }

    /**
     * Build the generator given the internal instance data
     */
    private function buildGenerator(): Snowflake
    {
        $generator = new Sonyflake(intval(sprintf(
            '%d%d',
            $this->datacenter,
            $this->worker
        )));

        $generator->setStartTimeStamp($this->stardate);
        $generator->setSequenceResolver($this->resolver);

        return $generator;
    }
}
