<?php
class fsapi{
    protected  $pin = null;
    protected  $host = null;
    protected  $sid = null;
    protected  $rw = null;
    protected  $validation = null;
    protected  $operation = null;
    protected  $verbose = false;


    /*
     * Getter
     */

    public function getpin(){
        return $this->pin;
    }

    public function gethost(){
        return $this->host ;
    }

    public function getrw(){
        return $this->rw;
    }

    public function getvalidation(){
        return $this->validation;
    }

    public function getoperation(){
        return $this->operation;
    }

    public function getverbose(){
        return $this->verbose;
    }

    /*
     * Setter
     */
    public function setpin($pin){
        $this->pin = $pin;
    }

    public function sethost($host){
        $this->host = $host;
    }


    public function setrw($rw){
        $this->rw = $rw;
    }

    public function setvalidation($validation){
        $this->validation = $validation;
    }

    public function setoperation($operation){
        $this->operation = $operation;
    }


    public function setverbose($verbose){
        $this->verbose = $verbose;
    }

    /*
     *  Constructor
     */
   function __construct() {
       $this->rw = array(
            /* System */
           'netRemote.sys.info.version' => array('GET'),
           'netRemote.sys.info.radioId' => array('GET'),
           'netRemote.sys.info.friendlyName' => array('GET','SET'),
           'netRemote.sys.net.wired.interfaceEnable' => array('GET'),
           'netRemote.sys.net.wired.macAddress' => array('GET'),
           'netRemote.sys.net.wlan.interfaceEnable' => array('GET'),
           'netRemote.sys.net.wlan.macAddress' => array('GET'),
           'netRemote.sys.net.wlan.connectedSSID' => array('GET'),
           'netRemote.sys.net.wlan.setEncType' => array('GET'),
           'netRemote.sys.net.wlan.setAuthType' => array('GET'),
           'netRemote.sys.net.wlan.rssi' => array('GET'),
           'netRemote.sys.net.ipConfig.dhcp' => array('GET'),
           'netRemote.sys.net.ipConfig.address' => array('GET'),
           'netRemote.sys.net.ipConfig.subnetMask' => array('GET'),
           'netRemote.sys.net.ipConfig.gateway' => array('GET'),
           'netRemote.sys.net.ipConfig.dnsPrimary' => array('GET'),
           'netRemote.sys.net.ipConfig.dnsSecondary' => array('GET'),
           'netRemote.sys.audio.volume' => array('GET','SET'),
           'netRemote.sys.audio.mute' => array('GET','SET'),
           'netRemote.sys.audio.eqPreset' => array('GET','SET'),
           'netRemote.sys.audio.eqLoudness' => array('GET','SET'),
           'netRemote.sys.audio.eqCustom.param0' => array('GET','SET'),
           'netRemote.sys.audio.eqCustom.param1' => array('GET','SET'),
           'netRemote.sys.caps.dabFreqList' => array('LIST_GET_NEXT'),
           'netRemote.sys.caps.volumeSteps' => array('GET'),
           'netRemote.sys.caps.fmFreqRange.lower' => array('GET'),
           'netRemote.sys.caps.fmFreqRange.upper' => array('GET'),
           'netRemote.sys.caps.fmFreqRange.stepSize' => array('GET'),
           'netRemote.sys.caps.eqPresets' => array('LIST_GET_NEXT'),
           'netRemote.sys.caps.eqBands' => array('LIST_GET_NEXT'),
           'netRemote.sys.caps.validModes' => array('LIST_GET_NEXT'),
           'netRemote.sys.mode' => array('GET','SET'),
           'netRemote.sys.power' => array('GET','SET'),
           'netRemote.sys.lang' => array('GET'),

           /* Play */
           'netRemote.play.frequency' => array('GET','SET'),
           'netRemote.play.serviceIds.fmRdsPi' => array('GET'),
           'netRemote.play.scrobble' => array('GET'),
           'netRemote.play.serviceIds.ecc' => array('GET'),
           'netRemote.play.repeat' => array('GET','SET'),
           'netRemote.play.info.name' => array('GET'),
           'netRemote.play.info.text' => array('GET'),
           'netRemote.play.status' => array('GET'),
           'netRemote.play.caps' => array('GET'),
           'netRemote.play.shuffle' => array('GET','SET'),
           'netRemote.play.control' => array('GET','SET'),
           'netRemote.play.info.album' => array('GET'),
           'netRemote.play.info.artist' => array('GET'),
           'netRemote.play.info.graphicUri' => array('GET'),
           'netRemote.play.position' => array('GET'),
           'netRemote.play.info.duration' => array('GET'),
           'netRemote.play.rate' => array('GET'),
           'netRemote.play.signalStrength' => array('GET'),

           /* Nav */
           'netRemote.nav.action.dabScan' => array('GET'),
           'netRemote.nav.status' => array('GET','SET'),
           'netRemote.nav.presets' => array('LIST_GET_NEXT'),
           'netRemote.nav.list' => array('LIST_GET_NEXT'),
           
           'netRemote.nav.action.selectItem' => array('GET','SET'),
           'netRemote.nav.action.navigate' => array('GET','SET'),
           'netRemote.nav.caps' => array('GET'),
           'netRemote.nav.state' => array('GET','SET'),
           
       );

       $this->validation = array(
            'netRemote.sys.audio.volume' => array('between',array(0,20)),
            'netRemote.sys.audio.mute' => array('bool'),
            'netRemote.sys.power' => array('bool'),
            'netRemote.play.shuffle' => array('bool'),
            'netRemote.play.repeat' => array('bool'),
            'netRemote.nav.state' => array('bool'),
            
            'netRemote.sys.audio.eqPreset' => array('between',array(0,5)),
            'netRemote.play.control' => array('between',array(0,4)),
       );

       $this->operation = array('SET','GET','LIST_GET','LIST_GET_NEXT','CREATE_SESSION','DELETE_SESSION','GET_NOTIFIES');


   }

    /*
     * Destructor
     */
    function __destruct(){
        if($this->sid != null ){
            $this->call('DELETE_SESSION', '');
        }
    }

    /*
     * Converting a field to the right datatype
     */
    public function encode($response,$node){

    
        foreach($response as $type => $message){
            echo $type." : ".$message."\n";
            switch($type){
                case "u8":
                case "u16":
                case "u32":

                case "s8":
                case "s16":
                case "s32":
                    return (float) trim($message);
                case "c8_array":
                    return trim($message);
                case "array":
                    return pack('H*', $message);
            }
        }
    }

    /*
     * Converting a list to an array
     */
    public function encode_list($response){
	$result =  array();
        foreach($response as $item){
	 if(is_array($item['field'])){
            foreach($item['field'] as $data){
                $a_data = (array) $data;
                foreach($a_data as $entry_key => $entry){
                    if($entry_key != '@attributes'){
                        $a_entry = (array)$entry;
                        $result[$item['@attributes']['key']][$a_data['@attributes']['name']] =     $a_entry[0];
                    }
                }
            }
	}else{
        $a_data = (array) $item['field'];
        foreach($a_data as $entry_key => $entry){
            if($entry_key != '@attributes'){
                $a_entry = (array)$entry;
                $result[$item['@attributes']['key']][$a_data['@attributes']['name']] =     $a_entry[0];
            }
        }
	}
    }
	return $result;
    }




    public function validate($attr,$method){
        switch($method[0]){
            case 'bool':
                if($attr == 0 || $attr == 1){
                    return array(true, (bool) $attr);
                }else{
                    return array(false,"Value ".$attr." is not boolean");
                }

            case 'between':
                if(is_numeric ($attr) && ($attr >= $method[1][0] && (int) $attr <= $method[1][1])){
                    return array(true, (int) $attr);
                }else{
                    return array(false,"Value ".$attr." is not between ".$method[1][0]." and ".$method[1][1]." ");
                }
            default:
                return array(false,"No validation rule found");
        }


    }


    /*
     * Function for making a request
     */
    public function call($operation, $node, $attr = array(),$folder = ""){
        if($this->pin == null){
            return array(false,'no pin given');
        }
        // if whe don't have any Session try to login with the given pin
        if($operation != 'CREATE_SESSION'){
            if($this->sid == null ){
                $session = $this->call('CREATE_SESSION', '');
                if($session[0] == true){
                    $this->sid = $session[1];
                }else{
                    return array(false, $session[1]);
                }
            }
        }

        // Check if operation (GET, SET, etc. ) is valid
        if(!in_array($operation,$this->operation)){
            return array(false,'Client: Unknown Operation '.$operation);
        }

        // Check if operation is allowed for this node (some nodes are read only)
        $rw = $this->rw;
        if($rw != null){
            if(($operation != 'CREATE_SESSION' && $operation != 'DELETE_SESSION' && $operation != 'GET_NOTIFIES') && (!isset($rw[$node]) || !in_array($operation,$rw[$node]))){
                return array(false,'Client: Operation '.$operation.' not in whiteliste for '.$node.'');
            }
        }

        $validation = $this->validation;
        if($operation == 'SET' && isset($validation[$node])){
            $valid = $this->validate($attr['value'], $validation[$node]);
            if(!$valid[0]){
                return array(false, "Validation for ".$node." failed: ".$valid[1]);

            }
        }

        // Append an additional folder to the url , in most cases -1
        if($folder != ""){
            $folder = "/".$folder;
        }
        $url = $this->host."/fsapi/".$operation."/".$node.$folder."?pin=".$this->pin;

        // Build the HTTP-GET variable-set
        if($this->sid != null){
            $url .= "&sid=".$this->sid;
        }
        if(count($attr) > 0){
            foreach($attr as $key => $value){
                $url .= "&".$key."=".$value;
            }
        }

            echo $url."\n";


        // Requesting...
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);

        if($info['http_code'] == 403 ){
            return array(false,'Connection: Incorrect Pin '.$this->pin);
        }else if(!($response === false)){
            $xml = new SimpleXMLElement($response);
            switch($xml->status){
                // everything is ok
               case 'FS_OK':
                    //print_r($xml);
                    if(isset($xml->value)){
                        return array(true,$this->encode((array)$xml->value,$node));
                    }elseif($xml->sessionId){
                        // Session-Id is not stored in the value field
                        return array(true,$this->encode((array)$xml->sessionId,$node));
                        
                    }else{
                        // it seems to be an array
                        $arr = array();
                        $i = 0;
                        foreach($xml->item as $item){
                            $arr[$i] = (array)$item;
                            $i++;
                        }
                        return array(true,$this->encode_list($arr));
                    }
                // we got an error
                case 'FS_FAIL':
                   return array(false,"Server: ".$xml->status);
                case 'FS_PACKET_BAD':
                    return array(false,"Server: ".$xml->status);
                case 'FS_NODE_BLOCKED':
                    return array(false,"Server: ".$xml->status);
                case 'FS_NODE_DOES_NOT_EXIST':
                    return array(false,"Server: ".$xml->status);
                case 'FS_TIMEOUT':
                    return array(false,"Server: ".$xml->status);
                case 'FS_LIST_END':
                    return array(false,"Server: ".$xml->status);
                default:
                    return array(false,"Server: ".$xml->status);
            }
        }
        $return =  array(false, curl_error($ch));
        curl_close($ch);
        return $return;
    }
}
?>
