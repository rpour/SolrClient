<?php
namespace ARP\SolrClient;

use ARP\SolrClient\SolrClient;

/**
 * SolrQuery class.
 * @author A.R.Pour
 */
class SolrQuery extends SolrClient {
    protected $params = array();
    protected $page = 1;
    protected $documentsFound = 0;
    protected $result = null;
    protected $content = '';
    protected $requestHandler = 'select';

    /**
     * Constructor.
     * @param array $options Options.
     */
    public function __construct($options) {
        parent::__construct($options);

        $this->params = array(
            'wt'        => 'json',
            "fl"        => "*,score",
            'json.nl'   => 'map',
            'start'     => 0,
            'rows'      => 100,
            'q'         => '*:*'
        );
    }

    /**
     * Search function.
     * @param  string   $query  Search query.
     * @param  integer  $offset   Start offset.
     * @param  integer  $limit   Hits per page.
     * @param  array    $params Parameters
     * @return Object   Result  documents.
     */
    public function find($query = null, $page = null, $hits = null, $params = array()) {
        $this->params = $this->mergeRecursive($this->params, $params);

        if(!is_null($hits))  $this->params['rows'] = (int)$hits;
        if(!is_null($query)) $this->params['q'] = $query;
        if(!is_null($page))  $this->page($page);
        
        $this->content = http_build_query($this->params);
        $this->content = preg_replace('/%5B([\d]{1,2})%5D=/', '=', $this->content);

        if($this->method === 'GET') {
            $response = $this->browser->get(
                $this->generateURL($this->requestHandler) . "?" . $this->content
            );
        } else {
            $response = $this->browser->post(
                $this->generateURL($this->requestHandler), 
                array('Content-type: application/x-www-form-urlencoded'), 
                $this->content
            );
        }

        if($response->isOk()) {
            $this->result = json_decode($response->getContent());
            return $this->result;
        } else {
            die('Query problem :' . $response->getContent());
        }
    }

    /**
     * Documents found.
     * @return integer Documents found.
     */
    public function documentsFound() {
        return isset($this->result->response->numFound)
            ? $this->result->response->numFound
            : 0;
    }

    /**
     * Returns the query result.
     * @return stdObject
     */
    public function getResult() {
        return $this->result;
    }

    /**
     * Returns the documents.
     * @return array documents.
     */
    public function getDocuments() {
        return isset($this->result->response->docs)
            ? $this->result->response->docs
            : array();
        
    }

    /**
     * Returns the facet fields.
     * @return array Facet fields.
     */
    public function getFacetFields() {
        return isset($this->result->facet_counts->facet_fields)
            ? $this->result->facet_counts->facet_fields
            : array();
    }

    /**
     * Enable debug query
     * @param  boolean $debug
     */
    public function debug($debug) {
        if($debug)
            $this->params['deubgQuery'] = 'true';
        else if(isset($this->params['deubgQuery']))
            unset($this->params['deubgQuery']);
    }

    /**
     * Set result page
     * @param  integer $page Result page
     */
    public function page($page) {
        $this->page = (int)$page > 0 ? (int)$page : 1;
        $this->offset(($this->page * $this->params['rows']) - $this->params['rows']);
    }

    /**
     * Set start offset.
     * @param  integer $offset Start position.
     */
    public function offset($offset) {
        $this->params['start'] = (int)$offset;
    }

    /**
     * Set result limit.
     * @param  integer $limit Result limit.
     */
    public function limit($limit) {
        $this->params['rows'] = (int)$limit;
    }

    /**
     * Select result fields.
     * @param  string $select Fields
     */
    public function select($select) {
        $this->params['fl'] = $select;
    }

    /**
     * Set result order.
     * @param  string $sortBy Order by string.
     */
    public function sortBy($sortBy) {
        $this->params['sort'] = $sortBy;
    }

    /**
     * Queryparser.
     * @param  string $queryParser Queryparser
     */
    public function queryParser($queryParser) {
        $this->params['defType'] = $queryParser;
    }

    /**
     * Faceting
     * @param  string  $fields   Fields
     * @param  integer $mincount Returns only fileds more than mincount.
     * @param  string  $sort     Fields.
     * http://wiki.apache.org/solr/SimpleFacetParameters
     */
    public function facet($fields, $mincount = 1, $sort = null) {
        $this->params['facet'] = 'on';
        $this->params['facet.field'] = $fields;
        $this->params['facet.mincount'] = $mincount;
        if(!is_null($sort)) $this->params['facet.sort'] = $sort;
    }

    /**
     * Escape searchstring.
     * @param  string $string Searchstring.
     * @return string         Escaped searchstring.
     */
    public function escape($string) {
        return preg_replace(
            '/(\+|-|&&|\|\||!|\(|\)|\{|}|\[|]|\^|"|~|\*|\?|:|\\\)/',
            '\\\$1', 
            $string
        );
    }

    /**
     * Escape search phrase.
     * @param  string $string Phrase string.
     * @return string         Escaped phrase.
     */
    public function escapePhrase($string) {
        return preg_replace(
            '/("|\\\)/',
            '\\\$1', 
            $string
        );
    }

    /**
     * Request handler for solr select query.
     * @param  string $handler
     * @return string Request handler
     */
    public function requestHandler($handler = null) {
        if(!is_null($handler))
            $this->requestHandler = $handler;

        return $this->requestHandler;
    }

    /**
     * Returns the solr query and the params.
     * @return string
     */
    public function queryInfo() {
        return urldecode($this->content) . 
            '<pre>' . print_r($this->params, true) . '</pre>';
    }
}