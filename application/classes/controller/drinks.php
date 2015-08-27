<?php defined('SYSPATH') OR die('No direct access allowed.');
class Controller_drinks extends controller_drinkrecept {
	public function action_index($did = NULL)
	{
        xml::toXML(array("template" => 'drink'), $this->xml_root);
		if($did == NULL){
            $rand = DB::select_array(array('did','name'))->from('drink')->order_by(DB::expr('RAND()'))->limit(1)->execute()->as_array();
 			$did = $rand[0]['did'];
		}

		$this->current_drink = $did;

		$sql = DB::select_array(array('did', 'name', 'instructions', 'image'))->from('drink')->where('did', '=', $did);
		$information = $sql->execute()->as_array();
		$contents = DB::select_array(array('ingredients.iid', 'contents.amount', 'ingredients.name'))
		    ->from('ingredients')
		    ->join('contents')
		    ->on('ingredients.iid', '=', 'contents.iid')
		    ->where('contents.did', '=', $did)
		    ->execute()->as_array();
		$cont = array();
		foreach($contents as $c){
            if(strlen($c['amount']) == 0) unset($c['amount']);
		    $cont[count($cont)."ing"] = $c;
		}
		unset($c, $contents);
        $notFound = array();
        if(!empty($this->bar->ingredients)){
            foreach($cont as $i){
                if(array_search($i['iid'], $this->bar->ingredients) === false){
                    $notFound[count($notFound)."ing"] = array('iid' => $i['iid'], 'name' => $i['name']);
                }
            }
        }
        if(count($notFound) > 0){
            xml::toXML(array('notfound' => $notFound), $this->xml_root);
        }

		$information = array("information" => array_merge($information, array("content" => $cont)));
		xml::toXML($information, $this->xml_root);
        $this->SSrender = true;
	}
} // End Welcome Controller
