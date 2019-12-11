<?php declare(strict_types=1);
/*
 * This file is part of the Shieldon package.
 *
 * (c) Terry L. <contact@terryl.in>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shieldon\Driver;

use PDO;
/**
 * Sqlite Driver.
 */
class SqliteDriver extends SqlDriverProvider
{
    /**
     * Constructor.
     *
     * @param PDO $pdo
     * @param bool $debug
     */
    public function __construct(PDO $pdo, bool $debug = false)
    {
        parent::__construct($pdo, $debug);
    }

      /**
     * Create SQL tables that Shieldon needs.
     *
     * @return bool
     */
    protected function installSql(): bool
    {
        try {
            $sql = "
                CREATE TABLE IF NOT EXISTS {$this->tableFilterLogs} (
                    log_ip VARCHAR(46) PRIMARY KEY,
                    log_data BLOB
                );
            ";

            $this->db->query($sql);

            $sql = "
                CREATE TABLE IF NOT EXISTS {$this->tableRuleList} (
                    log_ip VARCHAR(46) PRIMARY KEY, 
                    ip_resolve VARCHAR(255), 
                    type TINYINT(3), 
                    reason TINYINT(3), 
                    time INT(10),
                    attempts INT(10)
                );
            ";

            $this->db->query($sql);

            $sql = "
                CREATE TABLE IF NOT EXISTS {$this->tableSessions} (
                    id VARCHAR(40) PRIMARY KEY, 
                    ip VARCHAR(46),
                    time INT(10),
                    microtimesamp BIGINT(20)
                );
            ";

            $this->db->query($sql);

            return true;

        // @codeCoverageIgnoreStart
        } catch (\Exception $e) {
            return false;
        }
        // @codeCoverageIgnoreEnd
        
    }

    /**
     * Check required tables exist or not.
     *
     * @return bool
     */
    protected function checkTableExists(): bool
    {
        // Try a select statement against the table
        // Run it in try/catch in case PDO is in ERRMODE_EXCEPTION.
        // $debug should be false, otherwise an error occurs.
        
        try {
            $result = $this->db->query("SELECT 1 FROM $this->tableFilterLogs LIMIT 1");

        // @codeCoverageIgnoreStart
        } catch (Exception $e) {

            // We got an exception == table not found
            return false;
            
        }
        // @codeCoverageIgnoreEnd

        return ($result !== false);
    }
}