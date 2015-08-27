<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Kohana Controller class. The controller class must be extended to work
 * properly, so this class is defined as abstract.
 *
 *
 */
class controller_xsltcontroller extends Controller {

	// Allow all controllers to run in production by default
	const ALLOW_PRODUCTION = TRUE;

	var $forceTransform = false;
	var $xsltStylesheet = 'default';

	/**
	 * Loads URI, and Input into this controller.
	 *
	 * @return	void
	 */
	public function before() {

		// Create the XML DOM
		/*$this->dom = new DomDocument('1.0', 'UTF-8');
		$this->dom->formatOutput = true;

		// Create the XML root
		$this->xml = $this->dom->appendChild($this->dom->createElement('root'));
		$this->xml_root = $this->dom->appendChild($this->dom->createElement('root'));*/
	//	var_dump($this->dom);

	}

	/**
	 * Handles methods that do not exist.
	 *
	 * @param		string	method name
	 * @param		array	arguments
	 * @return	void
	 */
	public function __call($method, $args) {
		// Default to showing a 404 page

	}

	public function after() {
	    //var_dump($this->dom->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="/xsl/' . $this->xsltStylesheet . '.xsl"'), $this->xml);
	    //var_dump($this->dom);
		/*$this->dom->insertBefore(

		        $this->dom->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="/xsl/' . $this->xsltStylesheet . '.xsl"'), $this->xml);
*/

		if (
			$this->forceTransform == true ||
			strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox/2.0') ||
			strpos($_SERVER['HTTP_USER_AGENT'], 'Android 2.2') ||
			strpos($_SERVER['HTTP_USER_AGENT'], 'Googlebot') ||
			strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 4.0') ||
			strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 5.0') ||
			strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0') || /* This is to give opera the HTML version... and also I dont trust IE6 ;) */
			strpos($_SERVER['HTTP_USER_AGENT'], 'acebookexternalhit') /* Facebook resolving, facebook is incompetent and cannot handle XSLT */
			) {

			$xslt = new DOMDocument;
			$xslt->load('xsl/' . $this->xsltStylesheet . '.xsl');

			$proc	= new xsltprocessor();
			$proc->importStyleSheet($xslt);

			$html = $proc->transformToDoc($this->dom);
			$this->response->body = $html->saveXML();
		} else {
			$this->response->headers('content-type', 'text/xml; encoding=utf-8;');
			$this->response->body = $this->dom->saveXML();
		}
	}

	public function setXsltStylesheet($xsltStylesheet)
	{
		return ($this->xsltStylesheet = $xsltStylesheet);
	}
}
