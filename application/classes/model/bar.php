<?php
class model_bar extends model{
    public $ingredients;
    public $drinks;
    private $debug = false;

    function __construct(){
        if($this->debug) echo "construct";
        $this->loadIng();
        if(!is_array($this->ingredients))
            $this->ingredients = array();
        if(!is_array($this->drinks))
            $this->drinks = array();
    }
    function __destruct(){
        if($this->debug) echo "destruct";
        $this->__sleep();
    }
    function __sleep(){
        if($this->debug) echo "sleep";
        //~ echo "Closing the Bar";
        /*$sql = "delete from storage where sessid='".session_id()."'";
        DB::delete('storage')->where('sessid', '=', session_id())->execute();
        //$this->db->query($sql);
        $sql = "insert into storage set ingredients = '".serialize($this->ingredients)."', sessid='".session_id()."', time=NOW()";
        DB::insert('storage', array('ingredients', 'sessid', 'time'))->values(array(serialize($this->ingredients), session_id(), time()))->execute();*/
        //$this->db->query($sql);
        $_SESSION['ingredients'] = $this->ingredients;
    }

    function __wakeup(){
        if($this->debug) echo "wake";
        $this->loadIng();
    }
    function loadIng(){
        //$sql = "select ingredients from storage where sessid='".session_id()."' limit 1";
//        $ingR = $this->db->query($sql)->as_array(true);

        //$ingR = DB::select('ingredients')->from('storage')->where('sessid', '=', session_id())->limit(1)->execute()->as_array();

        if(isset($_SESSION['ingredients']))
            $this->ingredients = $_SESSION['ingredients'];
        else
            $this->ingredients = array();

        //if(isset($ingR[0]) && strlen($ingR[0]['ingredients']) > 0)
          //  $this->ingredients = unserialize($ingR[0]['ingredients']);
    }
    public function delIngredients($ing){
        if(empty($ing))
            return;

        if(!is_array($ing))
            $ing = array($ing);
        foreach($ing as $i){
            $key = array_search($i, $this->ingredients);
            if($key !== false){
                unset($this->ingredients[$key]);
            }
        }
    }

    public function addIngredients($ingredient){
        if(empty($ingredient))
            return;
        if(!is_array($ingredient)){ //If its not an array, make it an array.
            $ingredient = array($ingredient);
        }
        foreach($ingredient as $i){
            if(array_search($i, $this->ingredients) === false && is_numeric($i)){
                $this->ingredients[] = $i;
            }
        }
    }
    function getIngName($ings){
        if(empty($ings)){
            return array();
        }
        if(!is_array($ings)){
            $ings = array($ings);
        }
        $sql = "select iid, name from ingredients where iid in (".implode(",", $ings).")";
        $r = DB::select_array(array('iid', 'name'))->from('ingredients')->where('iid', 'IN', $ings)->execute()->as_array();
        //$r = $this->db->query($sql)->as_array(true);
        $return = array();
        foreach($r as $row){
            $return[$row['iid']] = $row['name'];
        }
        return $return;
    }
    public function getBarCabinet(){
        $barCab = array();
        foreach($this->getIngName($this->ingredients) as $iid => $ing){
            $barCab[count($barCab).'ing'] = array('@iid' => $iid, '@name' => $ing);
        }
        return $barCab;
    }
    function getDrinks($ings = NULL){
        if($ings == NULL or empty($ings)){
            if(empty($this->ingredients)){
                return array();
            } else {
                $ings = $this->ingredients;
            }
        }
        //~ $sql = 'select did, name from drink where did not in (select did from drink where did in (select did from contents where iid not in (select iid from ingredients where category in (select category from ingredients where iid in ('.implode(", ", $ings).')))))';
        $sql = "select did, name from drink where did not in(select did from drink where did in (select did from contents where iid not in (".implode(", ", $ings).")))";
        $r = DB::select_array(array('did', 'name'))
            ->from('drink')
            ->where('did', 'NOT IN',
                    DB::select('did')->from('drink')->where('did', 'IN', DB::select('did')->from('contents')->where('iid', 'NOT IN', $ings)
                    )
            )->execute()->as_array();

        //~ var_dump($sql);
        //$r = $this->db->query($sql)->as_array(true);
        foreach($r as $row){
            if(array_search($row['name'], $this->drinks) === false){
                $this->drinks[$row['did']] = $row['name'];
            }
        }
        return $this->drinks;
    }
    public function loadBar($bid){
        $sql = "select ingredients from bars where bid='".$bid."' limit 1";
        //$bar = $this->db->query($sql)->as_array(true);
        $bar = DB::select('ingredients')->from('bars')->where('bid', '=', $bid)->limit(1)->execute()->as_array();
        if(count($bar) == 1){
            $this->ingredients = unserialize($bar[0]['ingredients']);
        }
    }

}
?>