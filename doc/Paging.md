Paging
======

<pre><code>
$searchtext = '*:*';
$page = 1;
$hits = 10;

$solr = new SolrQuery($config);
$solr->find($searchtext);

foreach($solr->getDocuments() as $document)
    echo '<br/>' . $document->title;

$paging = new Paging($solr->documentsFound(), $hits, $page);

$index = $paging->get('index');

print_r($index);
</code></pre>