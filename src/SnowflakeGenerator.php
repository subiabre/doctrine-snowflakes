<?php

namespace Subiabre;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Exception;
use Godruoyi\Snowflake\RandomSequenceResolver;
use Godruoyi\Snowflake\Snowflake;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Generate numeric, time based ids that are ideal for distributed environments
 */
class SnowflakeGenerator extends AbstractIdGenerator
{
    /**
     * @var int Number of the datacenter
     */
    public $datacenter;

    /**
     * @var int Number of the worker machine
     */
    public $worker;

    /**
     * @var int UNIX date of start for timestamps
     */
    public $stardate;

    /**
     * @var Snowflake
     */
    private $generator;

    /**
     * @var SequenceResolverInterface
     */
    private $resolver;

    /**
     * @var int
     */
    private $id;

    public function __construct()
    {;
        $dotenv = new Dotenv();

        try {
            $dotenv->loadEnv(\dirname(__DIR__, 2) . '/.env');

            $datacenter = $_ENV['DATACENTER_ID'];
            $worker = $_ENV['WORKER_ID'];
            $startdate = \strtotime($_ENV['START_DATE']) * 1000;
        } catch (Exception $e) {
            $datacenter = 0;
            $worker = 0;
            $startdate = \strtotime('2000-01-01') * 1000;
        }

        $this->datacenter = $datacenter;
        $this->worker = $worker;
        $this->stardate = $startdate;
        
        $this->buildRandomResolver();
        $this->buildGenerator();
    }

    public function __toString(): string
    {
        return $this->getId();
    }

    public function generate(EntityManager $em, $entity)
    {
        return $this->getId();
    }

    /**
     * Build the resolver using the RandomSequenceResolver
     */
    private function buildRandomResolver()
    {
        $this->resolver = new RandomSequenceResolver;
    }

    /**
     * Build the generator given the internal instance data
     */
    private function buildGenerator()
    {
        $generator = new Snowflake(
            $this->datacenter,
            $this->worker
        );

        $generator->setStartTimeStamp($this->stardate);
        $generator->setSequenceResolver($this->resolver);

        $this->generator = $generator;
    }

    /**
     * Get the generated id
     * @return string
     */
    public function getId(): string
    {
        if ($this->id) return $this->id;

        return $this->new();
    }

    /**
     * Generate a new id
     * @return string
     */
    public function new(): string
    {
        $this->id = $this->generator->id();

        return $this->id;
    }
}
