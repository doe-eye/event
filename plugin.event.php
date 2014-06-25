<?php
/*****************************************************************************************
plugin event.php
shows 

@author doe-eye alias d4u alias aca78
inspired by already existing plugins (e.g. countdown widget)

******************************************************************************************/ 
Aseco::registerEvent('onStartup', 'countdown_startup');
Aseco::registerEvent('onEverySecond', 'countdown_update');
Aseco::registerEvent('onSync', 'countdown_ready');

Aseco::registerEvent('onEndMap', 'countdown_endMap');
Aseco::registerEvent('onBeginMap', 'countdown_beginMap');

Aseco::registerEvent('onPlayerConnect', 'countdown_connect');

global $eventCountdown;

function countdown_startup($aseco, $command) {
    global $eventCountdown;
    $eventCountdown = new EventCountdown($aseco);
}

function countdown_update($aseco, $command) {
    global $eventCountdown;
    $eventCountdown->updateTime();
}

function countdown_ready($aseco, $command) {
    global $eventCountdown;
    $eventCountdown->updateCountdown();
	$eventCountdown->showWidget();
	
}

function countdown_endMap($aseco, $command) {
    global $eventCountdown;
    $eventCountdown->setShow(false);
    $eventCountdown->hideWidget();
	
}

function countdown_beginMap($aseco, $command) {
    global $eventCountdown;
    $eventCountdown->setShow(true);
    $eventCountdown->updateCountdown();
	$eventCountdown->showWidget();

}

function countdown_connect($aseco, $command) {
    global $eventCountdown;
    $eventCountdown->updateCountdown();
	$eventCountdown->showWidget();
	
}

class EventCountdown {

    private $enabled = true;
    private $aseco;
    private $show = true;
	public $picXML;
	public $cdXML;
	
    /** @var DateTime */
    private $countdown;
	private $cdf_posn = '';
	private $cdf_scale = '';
	
	private $cdf_dl_text = '';
	private $cdf_dl_posn = '';
	private $cdf_dl_halign = '';
	private $cdf_dl_valign = '';
	private $cdf_dvl_posn = '';
	private $cdf_dvl_halign = '';
	private $cdf_dvl_valign = '';	

	private $cdf_hl_text = '';
	private $cdf_hl_posn = '';
	private $cdf_hl_halign = '';
	private $cdf_hl_valign = '';
	private $cdf_hvl_posn = '';
	private $cdf_hvl_halign = '';
	private $cdf_hvl_valign = '';	
	
	private $cdf_ml_text = '';
	private $cdf_ml_posn = '';
	private $cdf_ml_halign = '';
	private $cdf_ml_valign = '';
	private $cdf_mvl_posn = '';
	private $cdf_mvl_halign = '';
	private $cdf_mvl_valign = '';		
	
	
	private $pf_posn = '';
	private $pf_scale = '';
	
	private $pf_pq_sizen = '';
	private $pf_pq_image = '';
	private $pf_pq_halign = '';
	private $pf_pq_valign = '';
	
	private $pf_hl_sizen = '';
	private $pf_hl_url = '';
	private $pf_hl_halign = '';
	private $pf_hl_valign = '';
	private $pf_hl_focusareacolor1 = '';
	private $pf_hl_focusareacolor2 = '';


    function EventCountdown($aseco) {
        $this->aseco = $aseco;
        try {
			$this->enabled = true;
            $xml_array = $aseco->xml_parser->parseXml("event.xml");			
			
			$this->countdown = new DateTime($xml_array['SETTINGS']['END_DATE'][0]);
			
			$this->cdf_posn = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['POSN'][0];
			$this->cdf_scale = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['SCALE'][0];
			
			$this->cdf_dl_text = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['DAYS_LABEL'][0]['TEXT'][0];
			$this->cdf_dl_posn = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['DAYS_LABEL'][0]['POSN'][0];
			$this->cdf_dl_halign = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['DAYS_LABEL'][0]['HALIGN'][0];
			$this->cdf_dl_valign = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['DAYS_LABEL'][0]['VALIGN'][0];
			$this->cdf_dvl_posn = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['DAYS_VALUE_LABEL'][0]['POSN'][0];
			$this->cdf_dvl_halign = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['DAYS_VALUE_LABEL'][0]['HALIGN'][0];
			$this->cdf_dvl_valign = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['DAYS_VALUE_LABEL'][0]['VALIGN'][0];
			
			$this->cdf_hl_text = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['HOURS_LABEL'][0]['TEXT'][0];
			$this->cdf_hl_posn = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['HOURS_LABEL'][0]['POSN'][0];
			$this->cdf_hl_halign = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['HOURS_LABEL'][0]['HALIGN'][0];
			$this->cdf_hl_valign = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['HOURS_LABEL'][0]['VALIGN'][0];
			$this->cdf_hvl_posn = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['HOURS_VALUE_LABEL'][0]['POSN'][0];
			$this->cdf_hvl_halign = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['HOURS_VALUE_LABEL'][0]['HALIGN'][0];
			$this->cdf_hvl_valign = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['HOURS_VALUE_LABEL'][0]['VALIGN'][0];
			
			$this->cdf_ml_text = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['MINUTES_LABEL'][0]['TEXT'][0];
			$this->cdf_ml_posn = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['MINUTES_LABEL'][0]['POSN'][0];
			$this->cdf_ml_halign = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['MINUTES_LABEL'][0]['HALIGN'][0];
			$this->cdf_ml_valign= $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['MINUTES_LABEL'][0]['VALIGN'][0];
			$this->cdf_mvl_posn = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['MINUTES_VALUE_LABEL'][0]['POSN'][0];
			$this->cdf_mvl_halign = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['MINUTES_VALUE_LABEL'][0]['HALIGN'][0];
			$this->cdf_mvl_valign = $xml_array['SETTINGS']['COUNTDOWN_FRAME'][0]['MINUTES_VALUE_LABEL'][0]['VALIGN'][0];
			
			
			$this->pf_posn = $xml_array['SETTINGS']['PICTURE_FRAME'][0]['POSN'][0];
			$this->pf_scale = $xml_array['SETTINGS']['PICTURE_FRAME'][0]['SCALE'][0];
			
			$this->pf_pq_sizen = $xml_array['SETTINGS']['PICTURE_FRAME'][0]['PICTURE_QUAD'][0]['SIZEN'][0];
			$this->pf_pq_image = $xml_array['SETTINGS']['PICTURE_FRAME'][0]['PICTURE_QUAD'][0]['IMAGE'][0];
			$this->pf_pq_halign = $xml_array['SETTINGS']['PICTURE_FRAME'][0]['PICTURE_QUAD'][0]['HALIGN'][0];
			$this->pf_pq_valign = $xml_array['SETTINGS']['PICTURE_FRAME'][0]['PICTURE_QUAD'][0]['VALIGN'][0];
			
			$this->pf_hl_sizen = $xml_array['SETTINGS']['PICTURE_FRAME'][0]['HOVER_LABEL'][0]['SIZEN'][0];
			$this->pf_hl_url = $xml_array['SETTINGS']['PICTURE_FRAME'][0]['HOVER_LABEL'][0]['URL'][0];
			$this->pf_hl_halign = $xml_array['SETTINGS']['PICTURE_FRAME'][0]['HOVER_LABEL'][0]['HALIGN'][0];
			$this->pf_hl_valign = $xml_array['SETTINGS']['PICTURE_FRAME'][0]['HOVER_LABEL'][0]['VALIGN'][0];
			$this->pf_hl_focusareacolor1 = $xml_array['SETTINGS']['PICTURE_FRAME'][0]['HOVER_LABEL'][0]['FOCUSAREACOLOR1'][0];
			$this->pf_hl_focusareacolor2 = $xml_array['SETTINGS']['PICTURE_FRAME'][0]['HOVER_LABEL'][0]['FOCUSAREACOLOR2'][0];

        } catch (\Exception $e) {
            $aseco->console("Error parsing the XML, countdown disabled!");
        }
		
		$this->picXML = <<<XML
			<frame posn="$this->pf_posn" scale="$this->pf_scale">
				<quad sizen="$this->pf_pq_sizen" image="$this->pf_pq_image"  halign="$this->pf_pq_halign" valign="$this->pf_pq_valign" />
				<label sizen="$this->pf_hl_sizen"  url="$this->pf_hl_url" halign="$this->pf_hl_halign" valign="$this->pf_hl_valign" focusareacolor1="$this->pf_hl_focusareacolor1" focusareacolor2="$this->pf_hl_focusareacolor2"/>
			</frame>
XML;
    }

    function setShow($show) {
        $this->show = $show;
    }

    function updateTime() {
        if (!$this->enabled)
            return;
        $stamp = $this->countdown->getTimestamp();
        if ((time() - $stamp) < 0 && (time() - $stamp) % 60 == 0) {
            $this->updateCountdown();
			$this->showWidget();
        }

        if (time() - $stamp > 0) {
			$this->enabled = false;
            $this->aseco->client->query('ChatSendServerMessage',"Countdown ended!");
            $this->showWidget();
        }
    }

    function updateCountdown() {
        $now = new DateTime();

        /** @var DateInterval */
        $diff = $now->diff($this->countdown, true);

        $xml = <<<XML
			<frame posn="$this->cdf_posn" scale="$this->cdf_scale">
				<label text="$this->cdf_dl_text" posn="$this->cdf_dl_posn" halign="$this->cdf_dl_halign" valing="$this->cdf_dl_valign"/>
				<label text="\$o$diff->days" posn="$this->cdf_dvl_posn" halign="$this->cdf_dvl_halign" valing="$this->cdf_dvl_valign"/>
			
				<label text="$this->cdf_hl_text" posn="$this->cdf_hl_posn" halign="$this->cdf_hl_halign" valing="$this->cdf_hl_valign"/>
				<label text="\$o$diff->h" posn="$this->cdf_hvl_posn" halign="$this->cdf_hvl_halign" valing="$this->cdf_hvl_valign"/>
			   
				<label text="$this->cdf_ml_text" posn="$this->cdf_ml_posn" halign="$this->cdf_ml_halign" valing="$this->cdf_ml_valign"/>
				<label text="\$o$diff->i" posn="$this->cdf_mvl_posn" halign="$this->cdf_mvl_halign" valing="$this->cdf_mvl_valign"/>
			</frame>			
XML;
		
		$this->cdXML = $xml;
    }
	
	function showWidget(){
		$xml = '<manialink id="2460983406" version="1">';
		if($this->enabled){
			$xml .= $this->cdXML;
		}
		$xml .= $this->picXML;
		$xml .= '</manialink>';
		
		if($this->show){
			$this->aseco->client->query('SendDisplayManialinkPage', $xml, 0, false);
		}
	}

	
    function hideWidget() {
        $xml = <<<XML
        <manialink id="2460983406" version="1">
		
		</manialink>
XML;
        $this->aseco->client->query('SendDisplayManialinkPage',$xml, 0, false);
    }


		
}
