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
 * Objects implementing this class serve as a statement generator that is to be
 * used by a given data source, the interface is kept relatively simple: a means
 * to pass the appropriate data to the Query and a means to get the generated
 * query statement.
 */
interface Query {

    /**
     * Assigns the fields that should be accessed or used for the given query.
     *
     * @param array $fields
     */
    public function setFields(array $fields);

    /**
     * Assigns the primary conditional logic that should take place for the query.
     *
     * For example, in a Query producing SQL statements the `WHERE` `AND` & `OR`
     * conditional logic should be set using this method.
     *
     * If the query does not support conditional statement an UnsupportedOperationException
     * should be thrown.
     *
     * @param array $conditions
     * @throws UnsupportedOperationException
     */
    public function setConditions(array $conditions);

    /**
     * Assigns the secondary conditional logic that should take place for the
     * query.
     *
     * For example, in a Query production SQL statements the `JOIN` conditional
     * logic should be set using this method.
     *
     * If the query does not support conditional statements, or clauses in conditional
     * statements, an UnsupportedOperationException should be thrown.
     *
     * @param array $clauses
     */
    public function setClauses(array $clauses);

    /**
     * Should return the appropriate query statement, based on the information passed
     * to the object and type of statement the Query object should generate.
     *
     * @return string
     */
    public function getQueryStatement();


}

// End Query
