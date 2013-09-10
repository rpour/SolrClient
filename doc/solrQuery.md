Connect
=======
<code>
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
</code>

Find
====
<code>
    $documents = $solr->find('*:*');

    foreach($documents as $document) {
        echo '<br/>' . $document->title;
    }
</code>

Select
======
<code>
    $solr->select('*,score');

    $documents = $solr->find('*:*');

    echo '<br/>' . $document->documentsFound();

    foreach($documents as $document) {
        echo '<br/>' . $document->title;
    }
</code>

Offset and limit
================
<code>
    $solr->limit(10);    
    $solr->offset(0);
    $documents = $solr->find('*:*');
</code>
or
<code>
    $solr->limit(10);    
    $solr->page(1);
    $documents = $solr->find('*:*');
</code>
or
<code>
    $documents = $solr->find('*:*', 1, 10); 
</code>

Facets
======
<code>
    $solr->facet(array('text', 'keywords'));
    $documents = $solr->find('*:*');
    $facets = $solr->getFacetFields();

    foreach($facets->text as $key => $count) {
        echo "<br/>$key ($count)";
    }

    foreach($facets->keywords as $key => $count) {
        echo "<br/>$key ($count)";
    }
</code>

<code>
    $minCount = 5;

    $sort = 'count'; // 'index'

    $solr->facet(
        array('text', 'keywords'),
        $minCount,
        $sort
    );
</code>