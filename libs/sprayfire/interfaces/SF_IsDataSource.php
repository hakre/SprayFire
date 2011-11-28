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
 * This interface should be implemented by all DataSource objects added to the
 * DataSourceStore.
 *
 * These objects are responsible for establishing a connection to and performing
 * queries on a persistent data storage mechanism.
 *
 * It is expected that the DataSource will validate the child type of the parameters
 * that extend SF_CoreObject to match the interface expected by that particular
 * data source.  If the object passed is one that cannot be used by the data source
 * then an exception should be thrown.  This is done to allow the calling code
 * to implement their own DataSource and DataQuery objects that may be more
 * appropriate for that data source.  The SF_CoreObject is used as a type check
 * to ensure that (1) parameters passed are objects and (2) ensure that the
 * object passed is
 */
interface SF_IsDataSource {

    /**
     * The data source should be prepared to accept a configuration object that
     * will have information unique to it, this is NOT the framework's core
     * configuration object.
     *
     * @param SF_IsConfigurationStorage
     */
    public function __construct(SF_IsConfigurationStorage $DataSourceConfig);

    /**
     * Should perform a query, using the passed DataQuery object to get the values
     * needed to perform the query.
     *
     * @param SF_CoreObjet $DataQuery
     * @return boolean
     */
    public function performQuery(SF_IsDataQuery $DataQuery);

    /**
     * Should return the results associated with the last query, if an object is
     * not passed the data source should do its best to interpret the type of
     * primitive data that should be returned instead.
     *
     * For example, a MySQL data source performing a 'create' query should likely
     * return false or the auto-generated ID returned by LAST_INSERT_ID().  Whereas
     * a 'read' query should likely return an associative array with keys linked to
     * 'table.field_name' and values being the value returned by the query.
     *
     * @param SF_CoreObject $DataResponse
     * @return mixed
     */
    public function getQueryResults(SF_IsQueryResult $DataResponse = null);

    /**
     * Should kill whatever connection was established for this data source.
     */
    public function killConnection();
}

// End SF_IsDataSource
