Create document
===============
<code>
    use ARP\SolrClient\SolrDocument;

    $doc = new SolrDocument(); 
    $doc->id = 0; 
    $doc->title = "test"; 
    $doc->text = "hello world";
</code>

Boost document
==============
<code>
    $doc = new SolrDocument(); 
    $doc->setBoost(3.5);
</code>

Boost document field
====================
<code>
    $doc = new SolrDocument(); 
    $doc->id = 0; 
    $doc->title = "test"; 
    $doc->text = "hello world";
    $doc->setFieldBoost('text', 3.5);
</code>

Add field and boost
===================
<code>
    $doc = new SolrDocument(); 
    $doc->id = 0; 
    $doc->title = "test"; 
    $doc->add('text', "hello world", 3.5);
</code>