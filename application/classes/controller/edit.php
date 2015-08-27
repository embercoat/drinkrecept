<?php defined('SYSPATH') OR die('No direct access allowed.');
class controller_edit extends controller_drinkrecept {
	public function action_index()
	{
		$this->drink();
	}
	public function action_drink($did = NULL){
        xml::toXML(array("template" => 'editdrink'), $this->xml_root);
		if($did == NULL){
			header("Location: /");
            die();
		}  elseif($did == "new"){
            xml::toXML(array('information' => array ('did' => 'new')), $this->xml_root);
        } else {
            $information = DB::select_array(array('did', 'name', 'instructions', 'image'))->from('drink')->where('did', '=', $did)->limit(1)->execute()->as_array();
            $information = $information[0];
            $contents = DB::select_array(array('ingredients.iid', 'ingredients.name', 'contents.amount'))->from('ingredients')->join('contents')->on('ingredients.iid', '=', 'contents.iid')->where('contents.did', '=', $did)->order_by('contents.amount', 'desc')->execute()->as_array();
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
            $this->current_drink = $did;

            $information = array("information" => array_merge($information, array("content" => $cont)));
            xml::toXML($information, $this->xml_root);
        }
	}
    public function action_submitDrink(){
            if($this->safePost['did'] == "new"){
//                $this->safePost['did'] = $this->db->query('insert into drink set name="'.$this->safePost['name'].'", instructions="'.$this->safePost['instructions'].'"')->insert_id();
                list($this->safePost['did'], $null) = DB::insert('drink', array('name', 'instructions'))->values(array($this->safePost['name'], $this->safePost['instructions']))->execute();
            }
            for($i=0;$i<count($_POST['iid']); $i++){
            if($_POST['ing'][$i] != ""){
                if($_POST['iid'][$i] == ""){
                    $sql = "select iid from ingredients where lowername =\"".strtolower($this->safePost['ing'][$i])."\" limit 1";
//                    $new_ing = $this->db->query($sql);
                    $new_ing = DB::select('iid')->from('ingredients')->where('lowername', '=', $this->safePost['ing'][$i])->limit(1)->execute()->as_array();
                    if(count($new_ing) == 1){
                        $iid = $new_ing[0]['iid'];
                    } else {
                        $sql = "insert into ingredients set name='".$this->safePost['ing'][$i]."'";
                        //$iid = $this->db->query($sql)->insert_id();
                        list($iid, $null) = DB::insert('ingredients', array('name'))->values(array($this->safePost['ing'][$i]))->execute();
                    }
                    $sql = "insert into contents set did =".$this->safePost['did'].", iid=".$iid.", amount='".$this->safePost['amount'][$i]."'";
                    DB::insert('contents', array('did', 'iid', 'amount'))->values(array($this->safePost['did'], $iid, $this->safePost['amount'][$i]))->execute();
                } else {
                    $iid = $_POST['iid'][$i];
                    $sql = "update contents set amount='".$this->safePost['amount'][$i]."' where did=".$this->safePost['did']." and iid = ".$iid;
                    DB::update('ingredients')->set(array('amount' => $this->safePost['amount'][$i]))->where('did', '=', $this->safePost['did'])->where('iid', '=', $iid)->execute();
                }
                //$this->db->query($sql);
                $sql = "update ingredients set name='".$this->safePost['ing'][$i]."' where iid=".$iid;
                //$this->db->query($sql);
            } else {
                if($_POST['iid'][$i] != ""){
                    $sql = "delete from contents where did =".$this->safePost['did']." and iid = ".$this->safePost['iid'][$i];
                    DB::delete('contents')->where('did', '=', $this->safePost['did'])->where('iid', '=', $this->safePost['iid'][$i])->execute();
                    //$this->db->query($sql);
                }
            }
        }

        $sql = "update drink set instructions='".addslashes($_POST['instructions'])."' where did=".$this->safePost['did'];
        DB::update('drink')->set(array('instructions' => addslashes($_POST['instructions'])))->where('did', '=', $this->safePost['did'])->execute();
        //$this->db->query($sql);
        //header("location: http://kohana.scripter.se/drinks/".$this->safePost['did']);
        $this->response->headers('location', '/drinks/'.$this->safePost['did']);
    }
    public function action_ing($iid = NULL){
        if($iid === NULL){
            header('Location: /');
            die();
        } else {
            xml::toXML(array("template" => 'editIng'), $this->xml_root);
            $sql = "select i.*, c.name as categoryname from ingredients i
            left join categories c on c.cid = i.category where i.iid = ".$iid;
            $r = $this->db->query($sql)->as_array(true);
            xml::toXML(array('ing' => $r[0]), $this->xml_root);

            $catR = $this->db->query('select cid as "@cid", name as "@name" from categories order by name asc')->as_array(true);
            $cats = array();
            foreach($catR as $key => $val)
                $cats[$key.'category'] = $val;
            xml::toXML($cats, $this->xml_root);

            $sql = "select c.did, d.name from contents c join drink d on c.did = d.did where c.iid = ".$iid;
            $r = $this->db->query($sql)->as_array(true);
            $partOf = array();
            foreach($r as $key => $d){
                $partOf[$key.'drink'] = $d;
            }
            xml::toXML(array('partof' => $partOf), $this->xml_root);
        }
    }
    public function action_submitIng(){
        $iid = $this->safePost['iid'];
        if(!is_numeric($iid)){
            $this->addFail('Dude, dont edit the iid. It\'s futile.');
            header('Location: /');
            die();
        } else {
            $sql = 'update ingredients set name="'.$this->safePost['ingName'].'", info="'.$this->safePost['info'].'", category="'.$this->safePost['category'].'" where iid="'.$iid.'"';
            $this->db->query($sql);
            header('Location: /ing/'.$iid);
        }

    }
    public function action_category($cid){
        if($cid == NULL){
			header("Location: /");
            die();
		}  elseif($cid == "new"){
            xml::toXML(array("template" => 'editCategory'), $this->xml_root);
            xml::toXML(array('category' => array ('cid' => 'new')), $this->xml_root);
        } else {
            xml::toXML(array("template" => 'editCategory'), $this->xml_root);
            $sql = 'select * from categories where cid = '.$cid;
            list($cat) = $this->db->query($sql)->as_array(true);
            xml::toXML(array('category' => $cat), $this->xml_root);
        }
    }
    public function action_submitCategory(){
        if($this->safePost['cid'] == 'new'){
            $sql = 'insert into categories set name="'.$this->safePost['name'].'"';
            $this->safePost['cid'] = $this->db->query($sql)->insert_id();
        }
        if(!is_numeric($this->safePost['cid'])){
            $this->addFail('Dude, dont edit the cid. It\'s futile.');
            //~ header('Location: /');
            die();
        } else {
            $sql = 'update categories set name="'.$this->safePost['name'].'", info="'.$this->safePost['info'].'" where cid="'.$this->safePost['cid'].'"';
            $this->db->query($sql);
            header('Location: /category/'.$this->safePost['cid']);
        }
    }
	public function __call($method, $arguments)
	{
        if(is_numeric($method)){
            $this->drink($method);
        } else {
            // Disable auto-rendering
            $this->auto_render = FALSE;

            // By defining a __call method, all pages routed to this controller
            // that result in 404 errors will be handled by this method, instead of
            // being displayed as "Page Not Found" errors.
            echo __('This text is generated by __call. If you expected the index page, you need to use: :uri:',
                    array(':uri:' => Router::$current_uri));
        }
	}

} // End Welcome Controller
