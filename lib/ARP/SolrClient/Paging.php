<?php
namespace ARP\SolrClient;

/**
 * Paging class.
 * @author A.R.Pour
 * @version 1.0
 */
class Paging {
    private $paging = array();
 
    public function __construct($numFound, $hits, $page = null, $offset = null) {
        if(is_null($page) && is_null($offset))
            throw new Exception('Page or Offset needed!');

        $this->paging = array(
            'numFound' => (int)$numFound,
            'hits' => (int)$hits,
            'page' => $page,
            'offset' => $offset,
            'index' => array(
                'length' => 10
            )
        );

        $this->paging['pages'] = ceil($this->paging['numFound'] / $this->paging['hits']);

        if(!is_null($this->paging['offset']))
            $this->paging['page'] = ($this->paging['offset'] / $this->paging['hits']) + 1;

        if(!is_null($this->paging['page']))
            $this->paging['offset'] = ($this->paging['page'] * $this->paging['hits']) - $this->paging['hits'];

        if($this->paging['page'] > $this->paging['pages'])
            $this->paging['page'] = $this->paging['pages'];

        if($this->paging['offset'] > $this->paging['numFound'])
            $this->paging['offset'] = $this->paging['numFound'] - $this->paging['hits'];

        $this->refreshIndex();
    }

    private function refreshIndex() {
        // INDEX START
        if($this->paging['page'] > ($this->paging['index']['length']/2))
            $this->paging['index']['startPage'] = $this->paging['page'] - floor($this->paging['index']['length']/2);
        else 
            $this->paging['index']['startPage'] = 1;
        
        // INDEX END
        if(($this->paging['index']['startPage'] + $this->paging['index']['length']) > $this->paging['pages'] 
            && $this->paging['pages'] > $this->paging['index']['length'])
            $this->paging['index']['endPage'] = ceil($this->paging['pages']);
        else
            $this->paging['index']['endPage'] = $this->paging['index']['startPage'] + $this->paging['index']['length'];

        // END OF LIST?
        if($this->paging['index']['endPage'] - $this->paging['index']['startPage'] < $this->paging['index']['length'])
            $this->paging['index']['startPage'] = $this->paging['index']['startPage'] 
                - ($this->paging['index']['length'] - ($this->paging['index']['endPage'] - $this->paging['index']['startPage']));

        if($this->paging['index']['startPage'] < 1) {
            $this->paging['index']['startPage'] = 1;
        }

        $this->paging['index']['currentPage'] = $this->paging['page'];

        $this->paging['index']['firstPage'] = 1;
        $this->paging['index']['lastPage'] = $this->paging['pages'];
    }

    public function get($key = null) {
        if(is_null($key))
            return $this->paging;

        else if (isset($this->paging[$key]))
            return $this->paging[$key];

        return false;
    }
}
