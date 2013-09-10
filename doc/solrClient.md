Connect
=======
<code>
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

    $solr = new Solr($config);
</code>


Add or update document
======================
<code>
    $solr = new Solr($config); 
    $solr->addDocument($doc);
    $solr->commit();
    $solr->optimize();
</code>


Add or update document's
========================
<code>
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
</code>

Append document's
=================
<code>
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
</code>

Delete
======
<code>
    $solr->deleteAll();
    $solr->deleteByQuery('*:*'); // deleteAll()
    $solr->deleteByQuery('id:2');
</code>
