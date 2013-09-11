SolrDocument
============

Create document
---------------
<pre><code>
use ARP\SolrClient\SolrDocument;

$doc = new SolrDocument();
$doc->id = 0;
$doc->title = "test";
$doc->text = "hello world";
</code></pre>

Boost document
--------------
<pre><code>
$doc = new SolrDocument();
$doc->setBoost(3.5);
</code></pre>

Boost document field
--------------------
<pre><code>
$doc = new SolrDocument();
$doc->id = 0;
$doc->title = "test";
$doc->text = "hello world";
$doc->setFieldBoost('text', 3.5);
</code></pre>

Add field and boost
-------------------
<pre><code>
$doc = new SolrDocument();
$doc->id = 0;
$doc->title = "test";
$doc->add('text', "hello world", 3.5);
</code></pre>