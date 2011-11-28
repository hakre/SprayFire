<?php

/**
 * SprayFire is a custom built framework intended to ease the development
 * of websites with PHP 5.2.
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 *
 * @author Charles Sprayberry <cspray at gmail dot com>
 * @license OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

/**
 * A data structure object to hold various info that the data source object needs
 * to complete the query.
 */
interface SF_IsDataQuery {

    /**
     * @param string $dataSourceName
     */
    public function setDataSource($dataSourceName);

    /**
     * @return string
     */
    public function getDataSource();

    /**
     * @param string $queryType
     */
    public function setQueryType($queryType);

    /**
     * @return string
     */
    public function getQueryType();

    /**
     * @param array $tableFields
     */
    public function setFields(array $tableFields);

    /**
     * @return array
     */
    public function getFields();

    /**
     * @param array $whereConditions
     */
    public function setWhereConditions(array $whereConditions);

    /**
     * @return array
     */
    public function getWhereConditions();

    /**
     * @param array $orConditions
     */
    public function setOrConditions(array $orConditions);

    /**
     * @return array
     */
    public function getOrConditions();

    /**
     * @param array $andConditions
     */
    public function setAndConditions(array $andConditions);

    /**
     * @return array
     */
    public function getAndConditions();

    /**
     * @param array $joinConditions
     */
    public function setJoinConditions(array $joinConditions);

    /**
     * @return array
     */
    public function getJoinConditions();

}

// End SF_IsDataQuery
