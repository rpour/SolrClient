<?php
namespace ARP\SolrClient;

/**
 * Solr class.
 * @author A.R.Pour
 * @version 1.1
 */
class SolrClient {
    protected $browser = null;
    protected $host = null;
    protected $port = null;
    protected $path = null;
    protected $core = null;
    protected $version = null;
    protected $params = array();
    protected $cache;
    protected $cacheSize = 1024;

    /**
     * Constructor.
     * @param array $options Options.
     */
    public function __construct($options) {
        $this->host = isset($options['host']) ? $options['host'] : 'localhost';
        $this->port = isset($options['port']) ? $options['port'] : 8080;
        $this->path = isset($options['path']) ? $options['path'] : 'solr';
        $this->core = isset($options['core']) ? $options['core'] : '';
        $this->version = isset($options['version']) ? (int)$options['version'] : 4;
        $this->browser = isset($options['browser']) ? $options['browser'] : null;
    }

    /**
     * Ping solr server.
     * @return mixed Browser response.
     */
    public function ping() {
        return $this->browser->head($this->generateURL('admin/ping'));
    }

    /**
     * Add document to solr server.
     * @param SolrDocument $document Document as array.
     */
    public function addDocument(SolrDocument $document) {
        return $this->jsonUpdate($document->toJson());
    }

    /**
     * Add documents to solr server.
     * @param array $documents Documents as array's.
     */
    public function addDocuments($documents) {
        $json = '';
        foreach($documents as $document) {
            $json .= substr($document->toJson(),1,-1) . ',';
        }
        return $this->jsonUpdate('{' . substr($json,0,-1) . '}');
    }

    /**
     * Add document to cache.
     * @param SolrDocument $document Document as array.
     */
    public function appendDocument(SolrDocument $document) {
        $this->cache .= substr($document->toJson(),1,-1) . ',';
        if(strlen($this->cache) >= $this->cacheSize) {
            return $this->commitCachedDocuments();
        }

        return null;
    }

    /**
     * Delete by query.
     * @param  string $query query
     * @return [type]        [description]
     */
    public function deleteByQuery($query) {
        return $this->jsonUpdate('{"delete": { "query":"' . $query . '" }}');
    }

    /**
     * Delete all documents.
     * @return [type] [description]
     */
    public function deleteAll() {
        return $this->deleteByQuery('*:*');
    }

    /**
     * Add's all cached documents and commits everything.
     * @return [type] [description]
     */
    public function commit() {
        $this->commitCachedDocuments();
        return $this->jsonUpdate('{"commit": {}}');
    }

    /**
     * Optimize index
     * @param  boolean $waitFlush    [description]
     * @param  boolean $waitSearcher [description]
     * @return [type]                [description]
     */
    public function optimize($waitFlush = false, $waitSearcher = false) {
        // "waitFlush":' . ($waitFlush ? 'true' : 'false') . ',
        // 
        return $this->jsonUpdate('{"optimize": {
            "waitSearcher":' . ($waitSearcher ? 'true' : 'false') . ' 
        }}');
    }

    /**
     * Change cache size for appendDocument function.
     * @param [type] $size [description]
     */
    public function setCacheSize($size) {
        $this->cacheSize = (int)$size;
    }

    private function jsonUpdate($content) {
        if($this->version == 4)
            $url = $this->generateURL('update');
        else 
            $url = $this->generateURL('update/json');
        
        return $this->browser->post(
            $url, 
            array('Content-type:application/json'), 
            $content
        );
    }

    private function commitCachedDocuments() {
        if(strlen($this->cache) > 1) {
            $response = $this->jsonUpdate('{' . substr($this->cache,0,-1) . '}');
            $this->cache = '';
            return $response;
        }
        return null;
    }

    protected function generateURL($path = '') {
        return 'http://'
            . $this->host
            . ($this->port === null ?: ':' . $this->port)
            . ($this->path === null ?: '/' . $this->path)
            . ($this->core === null ?: '/' . $this->core)
            . ($path == '' ?: '/' . $path);
    }

    protected function mergeRecursive($arr1, $arr2) {
        foreach(array_keys($arr2) as $key) {
            if(isset($arr1[$key]) && is_array($arr1[$key]) && is_array( $arr2[$key]))
                $arr1[$key] = $this->mergeRecursive($arr1[$key], $arr2[$key]);
            else
                $arr1[$key] = $arr2[$key];
        }
        return $arr1;
    }
}