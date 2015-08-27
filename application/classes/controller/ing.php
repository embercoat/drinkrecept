<?php defined('SYSPATH') OR die('No direct access allowed.');
class controller_ing  extends controller_drinkrecept {
    public function action_index($ing = false)
	{
	    if($ing === false){
            xml::toXML(array("template" => 'list'), $this->xml_root);
            $ings = DB::select_array(array('iid', 'name'))->from('ingredients')->order_by('name', 'asc')->execute()->as_array();

            foreach($ings as &$i){
                $i = array('@add' => '/backend/adding/'.$i['iid'], '@href' => '/ing/'.$i['iid'], 'name' => $i['name']);
            }

            $drinks = array("list" => Model::factory('listgen')->genList($ings));
            xml::toXML($drinks, $this->xml_root);
            //xml::toXML($contents, $xml_root);
            xml::toXML(array('ingredients' => $ings), $this->xml_root);

            //~ $this->SSrender = true;
	    } else {
	        //~ header("content-type: text/html");
	        xml::toXML(array("template" => 'ing'), $this->xml_root);
	        $sql = "select i.*, c.name as category from ingredients i
	        left join categories c on i.category = c.cid
	        where iid = ".$ing;

	        //$r = $this->db->query($sql)->as_array(true);
	        $r = DB::select_array(array('ingredients.*', array('categories.name', 'category')))
	        ->from('ingredients')
	        ->join('categories', 'LEFT')
	        ->on('ingredients.category', '=', 'categories.cid')
	        ->where('iid', '=', $ing)
	        ->execute()->as_array();
	        xml::toXML(array('ing' => $r[0]), $this->xml_root);

	        //$sql = "select c.did, d.name from contents c join drink d on c.did = d.did where c.iid = ".$method;

	        //$r = $this->db->query($sql)->as_array(true);
	        $r = DB::select_array(array('contents.did', 'drink.name'))->from('contents')->join('drink')->on('contents.did', '=', 'drink.did')->where('contents.iid', '=', $ing)->execute()->as_array();
	        $partOf = array();
	        foreach($r as $key => $d){
	            $partOf[$key.'drink'] = $d;
	        }
	        xml::toXML(array('partof' => $partOf), $this->xml_root);
	    }
	}

	public function aciton_ing($method, $arguments)
	{


	}

} // End Welcome Controller
