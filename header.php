	<h1 id="title">Same-Origin Policy: Evaluation in Modern Browsers</h1>
	<div>
				<p>The term Same-Origin Policy (SOP) is used to denote a complex set of rules that govern the interaction of different Web Origins within a web application. A subset of these SOP rules controls the interaction between the host document and an embedded document, and this subset is the target of our research (SOP-DOM). In contrast to other important concepts like Web Origins (RFC 6454) or the Document Object Model (DOM), there is no formal specification of the SOP-DOM.</p>
				<p>In an empirical study, we ran 544 different test cases on each of the 10 major web browsers. We show that in addition to Web Origins, access rights granted by SOP-DOM depend on at least three attributes; the type of the embedding element (EE), and sandbox, and CORS attributes. We also show that due to the lack of a formal specification, different browser behaviors could be detcted in about 23% of our test cases. The issues discovered in Internet Explorer and Edge are acknowledged by Microsoft (MSRC Case 32703). We discuss our findings it in terms of read, write, and execute rights in different access control models.
</p><ul>
<li><a href="https://www.usenix.org/conference/usenixsecurity17/technical-sessions/presentation/schwenk">USENIX Security ’17 description</a></li>
<li><a href="https://www.usenix.org/system/files/conference/usenixsecurity17/sec17-schwenk.pdf">Paper as a PDF file</a></li>
<li><a href="https://www.usenix.org/biblio/export/bibtex/203852">BibTex</a></li>
<li><a href="https://github.com/RUB-NDS/your-sop.com">GitHub</a></li>
</ul><p></p>
</div>
