<?php defined('SYSPATH') OR die('No direct access allowed.');
abstract class controller_drinkrecept extends controller_xsltcontroller {
    protected $contenttype = 'text/xml';
    private $SSrender = false;
    protected $safePost = array();
    public function before(){
        session_start();
        parent::before();

        if(!empty($_POST)){
            $this->safePost = $this->makeSafe($_POST);
        }

        $this->response->headers('content-type', $this->contenttype);

        $this->current_drink = false;

        $this->dom = new DomDocument('1.0', 'UTF-8');
        $this->dom->formatOutput = true;
        $this->dom->appendChild(
               $this->dom->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="/templates/welcome.xsl"')
        );
        $this->xml_root = $this->dom->appendChild($this->dom->createElement('root'));
        $this->db = Database::instance();

    }
    public function after(){
        if(!empty($_SESSION['messages'])){
            $messageArr = array();
            foreach($_SESSION['messages'] as $level => $messages){
                foreach($messages as $key => $message){
                    $messageArr[$level][$key.'message'] = $message;
                }
            }
            xml::toXML(array('messages' => $messageArr), Model::factory('holder')->xml_root);
            unset($_SESSION['messages']);
        }
        if($this->current_drink){
            $next = DB::select('did')->from('drink')->where('did', '>', $this->current_drink)->order_by('did', 'asc')->limit(1)->execute()->as_array();
            if(!isset($next[0]))
                $next = DB::select(DB::expr('min(did)'), 'did')->from('drink')->execute()->as_array();
            $next = $next[0]['did'];

            $prev = DB::select('did')->from('drink')->where('did', '<', $this->current_drink)->order_by('did', 'desc')->limit(1)->execute()->as_array();
            if(!isset($prev[0]))
                $prev = DB::select(DB::expr('max(did)'), 'did')->from('drink')->execute()->as_array();
            $prev = $prev[0]['did'];

            $rand = DB::select_array(array('did','name'))->from('drink')->order_by(DB::expr('RAND()'))->limit(1)->execute()->as_array();
            $rand = $rand[0]['did'];
            $links = array('links' => array("next" => $next, "prev" => $prev, "rand" => $rand));

            $links = array('links' => array("next" => $next, "prev" => $prev, "rand" => $rand));
            xml::toXML($links, $this->xml_root);
        }
        xml::toXML(array("barcabinet" => Model::factory('bar')->getBarCabinet()), $this->xml_root);
        if($this->SSrender){
            $this->forceTransform = true;
            $this->render();
        } else {
            echo $this->dom->saveXML();
        }
        parent::after();
    }
    private function makeSafe($unsafeArr){
        $safeArr = array();
        foreach($unsafeArr as $key => $val){
            if(is_array($val)){
                $safeArr[$key] = $this->makeSafe($val);
            } else {
                $safeArr[$key] = addslashes($val);
            }
        }
        return $safeArr;
    }
    protected function addFail($msg){
        $_SESSION['messages']['fail'][] = $msg;
    }
    protected function addSuccess($msg){
        $_SESSION['messages']['success'][] = $msg;
    }
}
?>