<?php defined('SYSPATH') OR die('No direct access allowed.');
class controller_backend  extends controller {
    var $post = array();
    var $redirURL;
    //~ var $redir = false;
    var $redir = true;

    public function before(){
        session_start();
        $this->bar = Model::factory('bar');

        foreach($_POST as $key => $post){
            $this->post[$key] = $this->db->escape($post);
        }
    }
    public function after(){
        if(!isset($this->redirURL)){
            if(isset($_SERVER['HTTP_REFERER']))
                $this->redirURL = $_SERVER['HTTP_REFERER'];
            else
                $this->redirURL="/";
        }
        if($this->redir)
            $this->response->headers("location", $this->redirURL);
    }
	public function action_index()
	{
        //Nothing to see here.
	}
    public function action_adding($ing){
        if(is_numeric($ing)){
            $count = DB::select(DB::expr('count(1) as count'))->from('ingredients')->where('iid', '=', $ing)->execute()->as_array();
            if($count[0]['count'] == 1){
                $this->bar->addIngredients((int)$ing);
            }
        }
    }
    public function action_deling($ing){
        $this->bar->delIngredients((int)$ing);
    }
    public function action_delbar($bid){
        if(empty($this->post) or $bid == null){ //If we are missing the information we need, just return.
            return;
        }
        $sql = "select count(1) as count from bars where password='".md5($_POST['password'])."' and bid='".$this->db->escape($bid)."'";
        $count = DB::select(DB::expr('count(1) as count'))->from('bars')->where('password', '=', md5($this->post['password']))->where('bid', '=', $bid)->execute()->as_array();
        if($count[0]['count'] == 1){
            DB::delete('bars')->where('bid', '=', $bid)->execute();
            $_SESSION['messages']['success'][] = 'Baren har tagits bort';
        } else {
            //~ $_SESSION['messages']['fail'][] = 'Wrong password. Access Denied';
            $_SESSION['messages']['fail'][] = 'fel lösenord';
        }
    }
    public function action_loadbar($bid = null){
        if($bid == null){
            return;
        }
        $this->bar->loadBar($bid);
        $_SESSION['messages']['success'][] = 'Baren har laddats';
    }
    public function action_savebar(){

        if(strlen($_POST['barname']) <= 5){
            $_SESSION['messages']['fail'][] = 'The barname must me atleast 5 characters long';
            $this->redirURL = $_SERVER['HTTP_REFERER'];
            return;
        }
        $public = 1;
        $sql = 'insert into bars set name="'. $this->post['barname'].'", ingredients="'.serialize($this->bar->ingredients).'", password="'.md5($this->post['password1']).'", public="'.$public.'"';
        list($id, $num) = DB::insert('bars', array('name', 'ingredients', 'password', 'public'))
            ->values(array(
                    $_POST['barname'],
                    serialize($this->bar->ingredients),
                    md5($_POST['password']),
                    $public
             ))
             ->execute();
        //$result = $this->db->query($sql);
        $this->redirURL= "/bars/";
        $_SESSION['messages']['success'][] = 'Your bar has been saved. You can link to it with: http://'.$_SERVER['HTTP_HOST'].'/backend/loadbar/'.$id;

    }
    public function action_updateBar($bid = null){
        if(empty($_POST) or $bid == null){ //If we are missing the information we need, just return.
            return;
        }
        $sql = "select count(1) as count from bars where password='".md5($this->post['password'])."' and bid='".$this->db->escape($bid)."'";
//        $count = $this->db->query($sql)->as_array(true);
        $count = DB::select(DB::expr('count(1) as count'))->from('bars')->where('password', '=', md5($this->post['password']))->where('bid', '=', $bid)->execute()->as_array();

        if($count[0]['count'] == 1){
            $sql = 'update bars set ingredients = "'.serialize($this->bar->ingredients).'" where bid="'.$this->db->escape($bid).'"';
            //$this->db->query($sql);
            DB::update('bars')->set(array('ingredients' => serialize($this->bar_ingredients)))->where('bid', '=', $bid)->execute()->as_array();
            $_SESSION['messages']['success'][] = 'Baren har uppdaterats';
        } else {
            $_SESSION['messages']['fail'][] = 'Wrong password. Access Denied';
        }
    }

    public function action_json($what = FALSE){
        switch($what){
            case "drinks":{
                    $this->redir=false;
                    $this->content = 'text/html';
                    $r = DB::select_array(array('iid', 'name'))->from('ingredients')->order_by('name', 'asc')->execute()->as_array();
                    $drinks = array();
                    foreach($r as $data){
                        //~ $drinks[] = array('name' => iconv("UTF-8","ISO-8859-1//IGNORE",  $data['name']), 'iid' => $data['iid']);
                        //~ $drinks[] = iconv("UTF-8","ISO-8859-1//IGNORE",  $data['name']);
                        $drinks[] = $data['name'];
                    }
                    $json = json_encode($drinks);
                    echo $json;
                break;
            }
        }
    }


} // End Welcome Controller
