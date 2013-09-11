SolrClient
==========
* [back](../README.md)

Connect
-------
<pre><code>
use ARP\SolrClient\SolrClient;
use Buzz\Browser;
use Buzz\Client\Curl;

$config = array(
    'host'    => 'localhost',
    'port'    => 8080,
    'path'    => 'solr',
    'core'    => 'core0',
    'version' => 4,
    'browser' => new Browser(new Curl())
);

$solr = new SolrClient($config);
</code></pre>

Add or update document
----------------------
<pre><code>
$solr = new SolrClient($config); 
$solr->addDocument($doc);
$solr->commit();
$solr->optimize();
</code></pre>


Add or update document's
------------------------
<pre><code>
$solr = new Solr($config); 

$array = array(); 

foreach(array(1,2,3,4,5) as $id) { 
    $doc = new SolrDocument(); 
    $doc->id = $id; 
    $doc->title = "hallo"; 
    $array[] = $doc; 
} 

$solr->addDocuments($array);
$solr->commit();
$solr->optimize();
</code></pre>

Append document's
-----------------
<pre><code>
$solr = new Solr($config);
$solr->cacheSize(1024);

foreach(array(1,2,3,4,5) as $id) { 
    $doc = new SolrDocument(); 
    $doc->id = $id; 
    $doc->title = "tar gz"; 
    $solr->appendDocument($doc);
} 

$solr->commit();
$solr->optimize();
</code></pre>

Delete
------
<pre><code>
// delete all
$solr->deleteAll();

// delete all
$solr->deleteByQuery('*:*');

// delete document with id 2
$solr->deleteByQuery('id:2');
</code></pre>
