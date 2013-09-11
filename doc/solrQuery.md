SolrQuery
=========

Connect
-------
<pre><code>
use ARP\SolrClient\SolrQuery;
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

$solr = new SolrQuery($config);
</code></pre>

Find
----
<pre><code>
$solr->find('*:*');

foreach($solr->getDocuments() as $document)
    echo '<br/>' . $document->title;
</code></pre>

Select
------
<pre><code>
$solr->select('*,score');
$solr->find('*:*');

echo '<br/>' . $solr->documentsFound();

foreach($solr->getDocuments() as $document)
    echo '<br/>' . $document->title;
</code></pre>

Offset and limit
----------------
<pre><code>
$solr->limit(10);    
$solr->offset(30);
$result = $solr->find('*:*');
</code></pre>
or
<pre><code>
$solr->limit(10);    
$solr->page(4);
$result = $solr->find('*:*');
</code></pre>
or
<pre><code>
$result = $solr->find('*:*', 1, 10); 
</code></pre>

Facets
------
<pre><code>
$solr->facet(array('text', 'keywords'));
$solr->find('*:*');

$facets = $solr->getFacetFields();

foreach($facets->text as $key => $count)
    echo "<br/>$key ($count)";

foreach($facets->keywords as $key => $count)
    echo "<br/>$key ($count)";
</code></pre>

<pre><code>
$minCount = 5;

$sort = 'count'; // 'index'

$solr->facet(
    array('text', 'keywords'),
    $minCount,
    $sort
);
</code></pre>