<?php
class model_listGen extends model{
    public function genList($list){
        $listArr = array();
        foreach($list as $i){
            $i['name'] = utf8_decode($i['name']);
            if(is_numeric($i['name'][0])){
                $listArr['Nums'][((isset($listArr['Nums'])) ? count($listArr['Nums']) : 0)."drink"] = $i;
            } else {
                switch(ord(strtoupper($i['name'][0]))){
                    case 229:
                    case 197:
                        $i['name'] = utf8_encode($i['name']);
                        $listArr['Aring'][((isset($listArr['Aring'])) ? count($listArr['Aring']) : 0)."drink"] = $i;
                        break;
                    case 228:
                    case 196:
                        $i['name'] = utf8_encode($i['name']);
                        $listArr['Auml'][((isset($listArr['Auml'])) ? count($listArr['Auml']) : 0)."drink"] = $i;
                        break;
                    case 246:
                    case 214:
                        $i['name'] = utf8_encode($i['name']);
                        $listArr['Ouml'][((isset($listArr['Ouml'])) ? count($listArr['Ouml']) : 0)."drink"] = $i;
                        break;
                    default:
                        $i['name'] = utf8_encode($i['name']);
                        $listArr[strtoupper($i['name'][0])][((isset($listArr[strtoupper($i['name'][0])])) ? count($listArr[strtoupper($i['name'][0])]) : 0)."drink"] = $i;
                        break;
                }
            }
        }
        $formattedList = array();
        $group = 0;
            foreach($listArr as $cap => $liArr){
            $formattedList[$group.'group']['@cap'] = $cap;
            foreach($liArr as $li){
                if(!isset($formattedList[$group.'group']))
                    $formattedList[$group.'group'] = array();
                $formattedList[$group.'group'][count($formattedList[$group.'group'])."item"] = $li;
                //~ $formattedList[$group.'group'][count($formattedList[$group.'group'])."item"] = array('@href' => '/ing/'.$li['iid'], 'name' => $li['name'], '@add' => '/backend/adding/'.$li['iid']);
                if(count($formattedList[$group.'group']) >= 25){
                    $group++;
                }
            }
            if(!empty($formattedList[$group.'group'])){
              $group++;
            }
        }
        return $formattedList;
    }
}
?>