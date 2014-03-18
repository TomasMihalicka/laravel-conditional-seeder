<?php namespace Phirational\LaravelConditionalSeeder;

trait ConditionalSeeder
{
    /**
     * Migration Repository instance.
     *
     * @var \Illuminate\Database\Migrations\MigrationRepositoryInterface
     */
    protected $migrationRepository;

    /**
     * Return current instance of Migration Repository
     *
     * @return \Illuminate\Database\Migrations\MigrationRepositoryInterface
     */
    public function getMigrationRepository()
    {
        if ($this->migrationRepository == null)
        {
            $this->migrationRepository = $this->container->make('migration.repository');
        }

        return $this->migrationRepository;
    }

    /**
     * Get the ran migrations.
     *
     * @return array
     */
    protected function getRanMigrations()
    {
        return $this->getMigrationRepository()->getRan();
    }

    /**
     * Get the last migration batch.
     *
     * @return array
     */
    protected function getLastMigrations()
    {
        $last = $this->getMigrationRepository()->getLast();

        $batch = array();
        foreach ($last as $migration)
        {
            $batch[] = $migration->migration;
        }

        return $batch;
    }

    /**
     * Determine if migration (or migrations) is between ran migrations.
     *
     * @param string|array $migrations Migration name or array of migrations
     * @param bool $partialMatch Allow if just one matching migration is enough
     *
     * @return bool
     */
    public function isInRanMigrations($migrations, $partialMatch = false)
    {
        return $this->isInMigrationBatch($this->getRanMigrations(), $migrations, $partialMatch);
    }

    /**
     * Determine if migration (or migrations) is in last batch.
     *
     * @param string|array $migrations Migration name or array of migrations
     * @param bool $partialMatch Allow if just one matching migration is enough
     *
     * @return bool
     */
    public function isInLastMigrations($migrations, $partialMatch = false)
    {
        return $this->isInMigrationBatch($this->getLastMigrations(), $migrations, $partialMatch);
    }

    /**
     * Determine if migration (or migrations) is in supplied batch.
     *
     * @param array $batch Batch of migrations to search in
     * @param string|array $migrations Migration name or array of migrations
     * @param bool $partialMatch Allow if just one matching migration is enough
     *
     * @return bool
     */
    protected function isInMigrationBatch(array $batch, $migrations, $partialMatch = false)
    {
        if (!is_array($migrations))
        {
            $migrations = (array)$migrations;
        }

        $matched = array_intersect($migrations, $batch);

        // One matched migration is enough for partial match
        if ($partialMatch && count($matched) > 0)
        {
            return true;
        }

        if (count($matched) == count($migrations))
        {
            return true;
        }

        return false;
    }
}
