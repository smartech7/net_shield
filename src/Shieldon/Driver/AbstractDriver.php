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

use Shieldon\Driver\DriverInterface;

/**
 * Abstract Driver.
 */
abstract class AbstractDriver implements DriverInterface
{
    /**
     * {@inheritDoc}
     */
    public function get(string $ip, string $type = 'filter_log'): array
    {
        return $this->doFetch($ip, $type);
    }

    /**
     * {@inheritDoc}
     */
    public function getAll(string $type = 'filter_log'): array
    {
        return $this->doFetchAll($type);
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $ip, string $type = 'filter_log'): bool
    {
        return $this->checkExist($ip, $type);
    }

    /**
     * {@inheritDoc}
     */
    public function save(string $ip, array $data, string $type = 'filter_log', int $expire = 0): bool
    {
        return $this->doSave($ip, $data, $type, $expire);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $ip, string $type = 'filter_log'): bool
    {
        return $this->doDelete($ip, $type);
    }

    /**
     * {@inheritDoc}
     */
    public function rebuild(): bool
    {
        return $this->doRebuild();
    }

    /**
     * {@inheritDoc}
     */
    public function init(bool $dbCheck = true): void
    {
        $this->doInitialize($dbCheck);
    }

    /**
     * Implement fetch.
     *
     * @param string $ip The data id of the entry to fetch.
     *
     * @return array The data or an empty array.
     */
    abstract protected function doFetch(string $ip, string $type = 'filter_log'): array;

    /**
     * Implement fetch all.
     *
     * @param string $type The data type.
     *
     * @return array The data or an empty array.
     */
    abstract protected function doFetchAll(string $type = 'filter_log'): array;

    /**
     * Implement has.
     *
     * @param string $ip The data id of the entry to check for.
     *
     * @return bool
     */
    abstract protected function checkExist(string $ip, string $type = 'filter_log'): bool;

    /**
     * Implement save.
     *
     * @param string $ip     The data id.
     * @param array  $data   The data.
     * @param int    $expire The data will be deleted after expiring.
     *
     * @return bool
     */
    abstract protected function doSave(string $ip, array $data, string $type = 'filter_log', $expire = 0): bool;

    /**
     * Implement delete.
     *
     * @param string $ip
     *
     * @return bool
     */
    abstract protected function doDelete(string $ip, string $type = 'filter_log'): bool;

    /**
     * Rebuild data tables.
     *
     * @return bool
     */
    abstract protected function doRebuild(): bool;

    /**
     * Initial data tables.
     * 
     * @param bool $dbCheck
     *
     * @return void
     */
    abstract protected function doInitialize(bool $dbCheck = true): void;
}