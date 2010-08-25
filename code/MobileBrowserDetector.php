<?php
/**
 * Helper class for detecting known mobiles agents.
 * @package mobile
 */
class MobileBrowserDetector {

	/**
	 * List of known mobiles, found in the HTTP_USER_AGENT variable
	 * @see MobileBrowserDetector::is_mobile() for how they're used.
	 * 
	 * @return array
	 */
	private static function mobile_index_list() {
		return explode(',', '1207,3gso,4thp,501i,502i,503i,504i,505i,506i,6310,6590,770s,802s,a wa,acer,acs-,airn,alav,asus,attw,au-m,aur ,aus ,abac,acoo,aiko,alco,alca,amoi,anex,anny,anyw,aptu,arch,argo,bell,bird,bw-n,bw-u,beck,benq,bilb,blac,c55/,cdm-,chtm,capi,comp,cond,craw,dall,dbte,dc-s,dica,ds-d,ds12,dait,devi,dmob,doco,dopo,el49,erk0,esl8,ez40,ez60,ez70,ezos,ezze,elai,emul,eric,ezwa,fake,fly-,fly_,g-mo,g1 u,g560,gf-5,grun,gene,go.w,good,grad,hcit,hd-m,hd-p,hd-t,hei-,hp i,hpip,hs-c,htc ,htc-,htca,htcg,htcp,htcs,htct,htc_,haie,hita,huaw,hutc,i-20,i-go,i-ma,i230,iac,iac-,iac/,ig01,im1k,inno,iris,jata,java,kddi,kgt,kgt/,kpt ,kwc-,klon,lexi,lg g,lg-a,lg-b,lg-c,lg-d,lg-f,lg-g,lg-k,lg-l,lg-m,lg-o,lg-p,lg-s,lg-t,lg-u,lg-w,lg/k,lg/l,lg/u,lg50,lg54,lge-,lge/,lynx,leno,m1-w,m3ga,m50/,maui,mc01,mc21,mcca,medi,meri,mio8,mioa,mo01,mo02,mode,modo,mot ,mot-,mt50,mtp1,mtv ,mate,maxo,merc,mits,mobi,motv,mozz,n100,n101,n102,n202,n203,n300,n302,n500,n502,n505,n700,n701,n710,nec-,nem-,newg,neon,netf,noki,nzph,o2 x,o2-x,opwv,owg1,opti,oran,p800,pand,pg-1,pg-2,pg-3,pg-6,pg-8,pg-c,pg13,phil,pn-2,pt-g,palm,pana,pire,pock,pose,psio,qa-a,qc-2,qc-3,qc-5,qc-7,qc07,qc12,qc21,qc32,qc60,qci-,qwap,qtek,r380,r600,raks,rim9,rove,s55/,sage,sams,sc01,sch-,scp-,sdk/,se47,sec-,sec0,sec1,semc,sgh-,shar,sie-,sk-0,sl45,slid,smb3,smt5,sp01,sph-,spv ,spv-,sy01,samm,sany,sava,scoo,send,siem,smar,smit,soft,sony,t-mo,t218,t250,t600,t610,t618,tcl-,tdg-,telm,tim-,ts70,tsm-,tsm3,tsm5,tx-9,tagt,talk,teli,topl,tosh,up.b,upg1,utst,v400,v750,veri,vk-v,vk40,vk50,vk52,vk53,vm40,vx98,virg,vite,voda,vulc,w3c ,w3c-,wapj,wapp,wapu,wapm,wig ,wapi,wapr,wapv,wapy,wapa,waps,wapt,winc,winw,wonu,x700,xda2,xdag,yas-,your,zte-,zeto,aste,audi,avan,blaz,brew,brvw,bumb,ccwa,cell,cldc,cmd-,dang,eml2,fetc,hipt,http,ibro,idea,ikom,ipaq,jbro,jemu,jigs,keji,kyoc,kyok,libw,m-cr,midp,mmef,moto,mwbp,mywa,newt,nok6,o2im,pant,pdxg,play,pluc,port,prox,rozo,sama,seri,smal,symb,treo,upsi,vx52,vx53,vx60,vx61,vx70,vx80,vx81,vx83,vx85,wap-,webc,whit,wmlb,xda-');
	}

	public static function is_android() {
		return (stripos($_SERVER['HTTP_USER_AGENT'], 'android') !== false) ? true : false;
	}

	public static function is_iphone() {
		return (preg_match('/(ipod|iphone)/i', $_SERVER['HTTP_USER_AGENT'])) ? true : false;
	}

	public static function is_opera_mini() {
		return (stripos($_SERVER['HTTP_USER_AGENT'], 'opera mini') !== false) ? true : false;
	}

	public static function is_blackberry() {
		return (stripos($_SERVER['HTTP_USER_AGENT'], 'blackberry') !== false) ? true : false;
	}

	public static function is_palm() {
		return (preg_match('/(palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i', $_SERVER['HTTP_USER_AGENT'])) ? true : false;
	}

	public static function is_windows() {
		return (preg_match('/(windows ce; ppc;|windows ce; smartphone;|windows ce; iemobile)/i', $_SERVER['HTTP_USER_AGENT'])) ? true : false;
	}

	/**
	 * Is the current HTTP_USER_AGENT a known mobile device string?
	 * @see http://mobiforge.com/developing/story/setting-http-headers-advise-transcoding-proxies
	 * 
	 * @return bool
	 */
	public static function is_mobile() {
		$isMobile = false;
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$accept = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';

		switch(true) {
			case(self::is_iphone()):
				$isMobile = true;
				break;
			case(self::is_android()):
				$isMobile = true;
				break;
			case(self::is_opera_mini()):
				$isMobile = true;
				break;
			case(self::is_blackberry()):
				$isMobile = true;
				break;
			case(self::is_palm()):
				$isMobile = true;
				break;
			case(self::is_windows()):
				$isMobile = true;
				break;
			case(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|pda|psp|treo)/i', $agent)):
				$isMobile = true;
				break;
			case((strpos($accept, 'text/vnd.wap.wml') !== false) || (strpos($accept, 'application/vnd.wap.xhtml+xml') !== false)):
				$isMobile = true;
				break;
			case(isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])):
				$isMobile = true;
				break;
			case(in_array(strtolower(substr($agent, 0, 4)), self::mobile_index_list())):
				$isMobile = true;
				break;
		}

		if(!headers_sent()) {
			header('Cache-Control: no-transform');
			header('Vary: User-Agent, Accept');
		}

		return $isMobile;
	}
}