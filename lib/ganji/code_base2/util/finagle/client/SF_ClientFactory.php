<?php
/**
 *
 * User: tailor
 * Date: 14-3-19
 * Time: 下午2:42
 *
 */

/**
 * virtual interface for all ClientFactory
 */
interface SF_ClientFactory
{
    public function getClient($builder, $host, $port);
}
