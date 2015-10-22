<?php

namespace Icinga\Module\Director\Clicommands;

use Icinga\Cli\Command;
use Icinga\Module\Director\Db;
use Icinga\Module\Director\Objects\SyncRule;
use Icinga\Module\Director\Import\Sync;

class SyncruleCommand extends Command
{
    /**
     * The DB connection for db()
     *
     * @var Db
     */
    protected $db;

    /**
     * Run a particular syncrule
     *
     * Usage: icingacli director syncrule run <syncrule id>
     */
    public function runAction()
    {
        Sync::run(SyncRule::load($this->params->shift(), $this->db()));
    }

    /**
     * Return the DB connection $this->db (create a new one if there isn't any)
     *
     * @return Db
     */
    protected function db()
    {
        if ($this->db === null) {
            $this->app->setupZendAutoloader();
            $resourceName = $this->Config()->get('db', 'resource');
            if ($resourceName) {
                $this->db = Db::fromResourceName($resourceName);
            } else {
                $this->fail('Director is not configured correctly');
            }
        }

        return $this->db;
    }
}
