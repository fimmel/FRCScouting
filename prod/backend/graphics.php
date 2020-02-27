<?php

function convertHSL($h, $s, $l, $toHex=true)//HSL to Hex color for Gradients
{
    $h /= 360;
    $s /=100;
    $l /=100;

    $r = $l;
    $g = $l;
    $b = $l;
    $v = ($l <= 0.5) ? ($l * (1.0 + $s)) : ($l + $s - $l * $s);
    if ($v > 0){
        $m;
        $sv;
        $sextant;
        $fract;
        $vsf;
        $mid1;
        $mid2;

        $m = $l + $l - $v;
        $sv = ($v - $m ) / $v;
        $h *= 6.0;
        $sextant = floor($h);
        $fract = $h - $sextant;
        $vsf = $v * $sv * $fract;
        $mid1 = $m + $vsf;
        $mid2 = $v - $vsf;

        switch ($sextant)
        {
            case 0:
                $r = $v;
                $g = $mid1;
                $b = $m;
                break;
            case 1:
                $r = $mid2;
                $g = $v;
                $b = $m;
                break;
            case 2:
                $r = $m;
                $g = $v;
                $b = $mid1;
                break;
            case 3:
                $r = $m;
                $g = $mid2;
                $b = $v;
                break;
            case 4:
                $r = $mid1;
                $g = $m;
                $b = $v;
                break;
            case 5:
                $r = $v;
                $g = $m;
                $b = $mid2;
                break;
        }
    }
    $r = round($r * 255, 0);
    $g = round($g * 255, 0);
    $b = round($b * 255, 0);

    if ($toHex) {
        $r = ($r < 15)? '0' . dechex($r) : dechex($r);
        $g = ($g < 15)? '0' . dechex($g) : dechex($g);
        $b = ($b < 15)? '0' . dechex($b) : dechex($b);
        return "#$r$g$b";
    } else {
        return "rgb($r, $g, $b)";
    }
}
function gradient($level, $colormultiplier = 10)//Hue to RGB for Color Coding
{
    // 0 = red
    //133 = green
    global $colormultiplier;
    $hue = ((int)$level * $colormultiplier);
    $sat = 50;
    $lum = 40;

    return convertHSL($hue, $sat, $lum);
}

function gfx_ballscore($miss,$low,$out,$in,$points ,$period = ""){

    if($miss >0){ $svgfill_miss = gradient($miss); }else{$svgfill_miss = "#444444"; }
    if($low >0){ $svgfill_low = gradient($low); }else{$svgfill_low = "#444444"; }
    if($out >0){ $svgfill_out = gradient($out); }else{$svgfill_out = "#444444"; }
    if($in >0){ $svgfill_in = gradient($in); }else{$svgfill_in = "#444444"; }

    $gradient = "Default";
    if($period=="Aut"){
        $gradient = 'Auto';
        $barcolor = '#e6aa1e';
    }
    if($period=="Tel"){
        $gradient = 'Teleop';
        $barcolor = '#1ec5e6';
    }
    if($period=="Gam"){
        $gradient = 'Game';
        $barcolor = '#e61ee3';
    }

    //$svgfill_out = "#01ac00";
    print '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="50px" height="85px" >
<style type="text/css">
	.svg_miss{stroke:#000000;stroke-miterlimit:10;
	border 0;}
	.svg_out{stroke:#000000;stroke-miterlimit:10;}
	.svg_in{stroke:#000000;stroke-miterlimit:10;}
	.svg_low{stroke:#000000;stroke-miterlimit:10;}
	.colbar{stroke-width:0}
	text{
	font-size:12px;
	font-weight:bold;
	}
</style>

  <defs>
    <linearGradient id="Auto" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="70%" style="stop-color:#000000;stop-opacity:0" />
      <stop offset="100%" style="stop-color:#ED1C24;stop-opacity:.5" />
    </linearGradient>
    <linearGradient id="Teleop" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="70%" style="stop-color:#000000;stop-opacity:0" />
      <stop offset="100%" style="stop-color:#29ABE2;stop-opacity:.5" />
    </linearGradient>
    <linearGradient id="Game" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="70%" style="stop-color:#000000;stop-opacity:0" />
      <stop offset="100%" style="stop-color:#39B54A;stop-opacity:.5" />
    </linearGradient>
    <linearGradient id="Default" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" style="stop-color:rgb(120,120,120);stop-opacity:0" />
      <stop offset="100%" style="stop-color:rgb(120,120,120);stop-opacity:0.5" />
    </linearGradient>
  </defs>
<rect fill="#222222" x="0.5" y="0.5" width="49" height="84"/>
<polygon fill="'.$svgfill_out.'" class="svg_out" points="35.5,5 14.5,5 4,23 14.5,41 35.5,41 46,23 "/>
<circle fill="'.$svgfill_in.'" class="svg_in" cx="25" cy="23" r="9"/>
<rect fill="'.$svgfill_low.'" x="7" y="49" class="svg_low" width="36" height="14"/>
<rect fill="' . $barcolor . '" x="0" y="83" class="colbar" width="50" height="2"/>\';
<text text-anchor="start" x="2" y="12" fill="#cc9999">'.$miss.'</text>
<text text-anchor="middle" x="18" y="40" fill="#efefef">'.$out.'</text>
<text text-anchor="middle" x="24" y="28" fill="#efefef">'.$in.'</text>
<text text-anchor="middle" x="25" y="60" fill="#efefef">'.$low.'</text>
<text text-anchor="end" x="48" y="81" fill="#efefef">'.$points.'</text>
<text text-anchor="start" x="2" y="81" fill="#eee">'.$period.'</text>
</svg>';

}
function gfx_djbooth($stage){

    switch($stage){
        case "0":
            print '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="80px" height="85px"  xml:space="preserve">
            <style type="text/css">
                .st{stroke:#000000;stroke-miterlimit:10;}
            </style>
            <path class="st" fill="#444444" d="M42.06,6.91l-9.54,23.09l23.06-9.55C53.13,14.53,48.43,9.55,42.06,6.91z"/>
            <path class="st" fill="#444444" d="M22.95,6.9l9.57,23.1l9.54-23.09C36.15,4.46,29.32,4.26,22.95,6.9z"/>
            <path class="st" fill="#444444" d="M9.4,20.44l23.07,9.58L22.9,6.92C17,9.36,12.03,14.06,9.4,20.44z"/>
            <path class="st" fill="#444444" d="M9.41,39.57l23.06-9.55L9.4,20.44C6.95,26.35,6.77,33.19,9.41,39.57z"/>
            <path class="st" fill="#444444" d="M22.95,53.09l9.54-23.09L9.43,39.56C11.88,45.47,16.58,50.45,22.95,53.09z"/>
            <path class="st" fill="#444444" d="M42.06,53.1l-9.57-23.1l-9.54,23.09C28.86,55.54,35.69,55.74,42.06,53.1z"/>
            <path class="st" fill="#444444" d="M55.61,39.56l-23.07-9.58l9.57,23.1C48.01,50.64,52.98,45.94,55.61,39.56z"/>
            <path class="st" fill="#444444" d="M55.6,20.43l-23.06,9.55l23.07,9.58C58.05,33.65,58.24,26.81,55.6,20.43z"/>
            <path class="st" fill="#444444" d="M68.13,11.01L68.13,11.01l3.62-1.97L60.63,5L57.5,16.79l4.32-2.34l0,0c2.27,4.5,3.56,9.61,3.56,15.04
            c0,5.78-1.46,11.2-4.02,15.9L67.58,49c3.13-5.77,4.92-12.43,4.92-19.52C72.5,22.81,70.92,16.54,68.13,11.01z"/>
            <text text-anchor="middle" x="32" y="70" fill="#efefef">0</text>
            </svg>
            ';
            break;
        case "1":
            print '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="80px" height="85px"  xml:space="preserve">
            <style type="text/css">
                .st{stroke:#000000;stroke-miterlimit:10;}
            </style>
            <path class="st" fill="#39B54A" d="M42.06,6.91l-9.54,23.09l23.06-9.55C53.13,14.53,48.43,9.55,42.06,6.91z"/>
            <path class="st" fill="#29ABE2" d="M22.95,6.9l9.57,23.1l9.54-23.09C36.15,4.46,29.32,4.26,22.95,6.9z"/>
            <path class="st" fill="#FFFF00" d="M9.4,20.44l23.07,9.58L22.9,6.92C17,9.36,12.03,14.06,9.4,20.44z"/>
            <path class="st" fill="#ED1C24" d="M9.41,39.57l23.06-9.55L9.4,20.44C6.95,26.35,6.77,33.19,9.41,39.57z"/>
            <path class="st" fill="#39B54A" d="M22.95,53.09l9.54-23.09L9.43,39.56C11.88,45.47,16.58,50.45,22.95,53.09z"/>
            <path class="st" fill="#29ABE2" d="M42.06,53.1l-9.57-23.1l-9.54,23.09C28.86,55.54,35.69,55.74,42.06,53.1z"/>
            <path class="st" fill="#FFFF00" d="M55.61,39.56l-23.07-9.58l9.57,23.1C48.01,50.64,52.98,45.94,55.61,39.56z"/>
            <path class="st" fill="#ED1C24" d="M55.6,20.43l-23.06,9.55l23.07,9.58C58.05,33.65,58.24,26.81,55.6,20.43z"/>
            <path class="st" fill="#444444" d="M68.13,11.01L68.13,11.01l3.62-1.97L60.63,5L57.5,16.79l4.32-2.34l0,0c2.27,4.5,3.56,9.61,3.56,15.04
            c0,5.78-1.46,11.2-4.02,15.9L67.58,49c3.13-5.77,4.92-12.43,4.92-19.52C72.5,22.81,70.92,16.54,68.13,11.01z"/>
            <text text-anchor="middle" x="32" y="70" fill="#efefef">0</text>
            </svg>
            ';
            break;
        case "2":
            print '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="80px" height="85px"  xml:space="preserve">
            <style type="text/css">
                .st{stroke:#000000;stroke-miterlimit:10;}
            </style>
            <path class="st" fill="#39B54A" d="M42.06,6.91l-9.54,23.09l23.06-9.55C53.13,14.53,48.43,9.55,42.06,6.91z"/>
            <path class="st" fill="#29ABE2" d="M22.95,6.9l9.57,23.1l9.54-23.09C36.15,4.46,29.32,4.26,22.95,6.9z"/>
            <path class="st" fill="#FFFF00" d="M9.4,20.44l23.07,9.58L22.9,6.92C17,9.36,12.03,14.06,9.4,20.44z"/>
            <path class="st" fill="#ED1C24" d="M9.41,39.57l23.06-9.55L9.4,20.44C6.95,26.35,6.77,33.19,9.41,39.57z"/>
            <path class="st" fill="#39B54A" d="M22.95,53.09l9.54-23.09L9.43,39.56C11.88,45.47,16.58,50.45,22.95,53.09z"/>
            <path class="st" fill="#29ABE2" d="M42.06,53.1l-9.57-23.1l-9.54,23.09C28.86,55.54,35.69,55.74,42.06,53.1z"/>
            <path class="st" fill="#FFFF00" d="M55.61,39.56l-23.07-9.58l9.57,23.1C48.01,50.64,52.98,45.94,55.61,39.56z"/>
            <path class="st" fill="#ED1C24" d="M55.6,20.43l-23.06,9.55l23.07,9.58C58.05,33.65,58.24,26.81,55.6,20.43z"/>
            <path class="st" fill="#39B54A" d="M68.13,11.01L68.13,11.01l3.62-1.97L60.63,5L57.5,16.79l4.32-2.34l0,0c2.27,4.5,3.56,9.61,3.56,15.04
            c0,5.78-1.46,11.2-4.02,15.9L67.58,49c3.13-5.77,4.92-12.43,4.92-19.52C72.5,22.81,70.92,16.54,68.13,11.01z"/>
            <text text-anchor="middle" x="32" y="70" fill="#efefef">10</text>
            </svg>
            ';
            break;
        case "3":
            print '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="80px" height="85px"  xml:space="preserve">
            <style type="text/css">
                .st{stroke:#000000;stroke-miterlimit:10;}
            </style>
            <path class="st" fill="#444444" d="M42.06,6.91l-9.54,23.09l23.06-9.55C53.13,14.53,48.43,9.55,42.06,6.91z"/>
            <path class="st" fill="#444444" d="M22.95,6.9l9.57,23.1l9.54-23.09C36.15,4.46,29.32,4.26,22.95,6.9z"/>
            <path class="st" fill="#444444" d="M9.4,20.44l23.07,9.58L22.9,6.92C17,9.36,12.03,14.06,9.4,20.44z"/>
            <path class="st" fill="#444444" d="M9.41,39.57l23.06-9.55L9.4,20.44C6.95,26.35,6.77,33.19,9.41,39.57z"/>
            <path class="st" fill="#444444" d="M22.95,53.09l9.54-23.09L9.43,39.56C11.88,45.47,16.58,50.45,22.95,53.09z"/>
            <path class="st" fill="#444444" d="M42.06,53.1l-9.57-23.1l-9.54,23.09C28.86,55.54,35.69,55.74,42.06,53.1z"/>
            <path class="st" fill="#444444" d="M55.61,39.56l-23.07-9.58l9.57,23.1C48.01,50.64,52.98,45.94,55.61,39.56z"/>
            <path class="st" fill="#444444" d="M55.6,20.43l-23.06,9.55l23.07,9.58C58.05,33.65,58.24,26.81,55.6,20.43z"/>
            <polygon class="st" fill="#444444"  points="76.98,26.36 69.64,26.36 69.64,20.47 60.09,30.02 69.64,39.57 69.64,33.67 76.98,33.67 "/>
            <text text-anchor="middle" x="32" y="70" fill="#efefef">10</text>
            </svg>
            ';
            break;
        case "4":
            print '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="80px" height="85px"  xml:space="preserve">
            <style type="text/css">
                .st{stroke:#000000;stroke-miterlimit:10;}
            </style>
            <path class="st" fill="#39B54A" d="M42.06,6.91l-9.54,23.09l23.06-9.55C53.13,14.53,48.43,9.55,42.06,6.91z"/>
            <path class="st" fill="#29ABE2" d="M22.95,6.9l9.57,23.1l9.54-23.09C36.15,4.46,29.32,4.26,22.95,6.9z"/>
            <path class="st" fill="#FFFF00" d="M9.4,20.44l23.07,9.58L22.9,6.92C17,9.36,12.03,14.06,9.4,20.44z"/>
            <path class="st" fill="#ED1C24" d="M9.41,39.57l23.06-9.55L9.4,20.44C6.95,26.35,6.77,33.19,9.41,39.57z"/>
            <path class="st" fill="#39B54A" d="M22.95,53.09l9.54-23.09L9.43,39.56C11.88,45.47,16.58,50.45,22.95,53.09z"/>
            <path class="st" fill="#29ABE2" d="M42.06,53.1l-9.57-23.1l-9.54,23.09C28.86,55.54,35.69,55.74,42.06,53.1z"/>
            <path class="st" fill="#FFFF00" d="M55.61,39.56l-23.07-9.58l9.57,23.1C48.01,50.64,52.98,45.94,55.61,39.56z"/>
            <path class="st" fill="#ED1C24" d="M55.6,20.43l-23.06,9.55l23.07,9.58C58.05,33.65,58.24,26.81,55.6,20.43z"/>
            <polygon class="st" fill="#444444"  points="76.98,26.36 69.64,26.36 69.64,20.47 60.09,30.02 69.64,39.57 69.64,33.67 76.98,33.67 "/>
            <text text-anchor="middle" x="32" y="70" fill="#efefef">10</text>
            </svg>
            ';
            break;
        case "5":
            print '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="80px" height="85px"  xml:space="preserve">
            <style type="text/css">
                .st{stroke:#000000;stroke-miterlimit:10;}
            </style>
            <path class="st" fill="#39B54A" d="M42.06,6.91l-9.54,23.09l23.06-9.55C53.13,14.53,48.43,9.55,42.06,6.91z"/>
            <path class="st" fill="#29ABE2" d="M22.95,6.9l9.57,23.1l9.54-23.09C36.15,4.46,29.32,4.26,22.95,6.9z"/>
            <path class="st" fill="#FFFF00" d="M9.4,20.44l23.07,9.58L22.9,6.92C17,9.36,12.03,14.06,9.4,20.44z"/>
            <path class="st" fill="#ED1C24" d="M9.41,39.57l23.06-9.55L9.4,20.44C6.95,26.35,6.77,33.19,9.41,39.57z"/>
            <path class="st" fill="#39B54A" d="M22.95,53.09l9.54-23.09L9.43,39.56C11.88,45.47,16.58,50.45,22.95,53.09z"/>
            <path class="st" fill="#29ABE2" d="M42.06,53.1l-9.57-23.1l-9.54,23.09C28.86,55.54,35.69,55.74,42.06,53.1z"/>
            <path class="st" fill="#FFFF00" d="M55.61,39.56l-23.07-9.58l9.57,23.1C48.01,50.64,52.98,45.94,55.61,39.56z"/>
            <path class="st" fill="#ED1C24" d="M55.6,20.43l-23.06,9.55l23.07,9.58C58.05,33.65,58.24,26.81,55.6,20.43z"/>
            <polygon class="st" fill="#39B54A"  points="76.98,26.36 69.64,26.36 69.64,20.47 60.09,30.02 69.64,39.57 69.64,33.67 76.98,33.67 "/>
            <text text-anchor="middle" x="32" y="70" fill="#efefef">20 + RP</text>
            </svg>
            ';
            break;

        default:

            break;
    }
}
function gfx_climb($pos,$level)
{//1-No Attempt, 2-Parked, 3-Attempted, 4-Successful

    $offset = 0;
    $poscolorleft = "#444444";
    switch ($pos) {
        case 0:
            //leave gray
            break;
        case 1:
            //$botcolor = "#1ff258";
            break;
        case 2:
            $botcolor = "#1ff258";
            break;
        case 3:
            $botcolor = "#f23f1f";
            break;
        case 4:
            $botcolor = "#1ff258";
            break;
    }
    print '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="40px" height="85px"  xml:space="preserve">
<style type="text/css">
	.st0park{display:none;fill:none;stroke:#888888;stroke-miterlimit:10;}
	.st1park{stroke:#000;stroke-miterlimit:10;}
	.st0hang{fill:none;stroke:#CCCCCC;stroke-miterlimit:10;}
	.st1hang{stroke:#000;stroke-miterlimit:10;}
	.bar{stroke:#000000;stroke-miterlimit:10;}
</style>';
    switch ($pos) {
        case 1:
            //leave gray
            break;
        case 2:
            print '<line class="st0park" x1="20" y1="34.8" x2="20" y2="55.03"/>';
            print '<rect fill="' . $botcolor . '" x="6.68" y="55.03" class="st1park" width="26.64" height="12.16"/>';
            $points = 5;
            break;
        case 3:
            print '<line class="st0hang" x1="20" y1="' . (18 + $offset) . '.8" x2="20" y2="' . (39 + $offset) . '.03"/>';
            print '<rect fill="' . $botcolor . '" x="6.68" y="' . (39 + $offset) . '.03" class="st1hang" width="26.64" height="12.16"/>';
            $points = 5;

            break;
        case 4:
            print '<line class="st0hang" x1="20" y1="' . (18 + $offset) . '.8" x2="20" y2="' . (39 + $offset) . '.03"/>';
            print '<rect fill="' . $botcolor . '" x="6.68" y="' . (39 + $offset) . '.03" class="st1hang" width="26.64" height="12.16"/>';
            $points = 25;

            break;
    }


    switch ($level) {//1-No Chance, 2-Attempt, 3-Successful
        case 1:
            $barcolor = "#f23f1f";
            //$bar = '<rect fill="' . $barcolor . '" x="5" y="15" class="bar" width="90" height="4"/>';

            $bar = '<rect x="5" y="15" fill="' . $barcolor . '" transform="matrix(0.95 -0.3123 0.3123 0.95 -2.8083 16.4658)" class="bar" width="30" height="4"/>';
            break;
        case 2:
            $barcolor = "#f2d61f";
           // $bar = '<rect fill="' . $barcolor . '" x="5" y="15" class="bar" width="90" height="4"/>';
            $bar = '<rect x="5" y="15" fill="' . $barcolor . '" transform="matrix(0.95 -0.3123 0.3123 0.95 -2.8083 16.4658)" class="bar" width="30" height="4"/>';
            break;
        case 3:
            $barcolor = "#1ff258";
            $bar = '<rect fill="' . $barcolor . '" x="5" y="15" class="bar" width="30" height="4"/>';
            $points += 15;
            break;
    }
    print $bar;
    print '<text text-anchor="start" x="2" y="81" fill="#efefef">' . $points . '</text>';
    print '</svg> ';

}
function gfx_coathanger($level, $bot1, $bot2, $bot3, $mypos){

    //coathanger($match['alliance']['endgameRungIsLevel'],$match['alliance']['endgameRobot1'],$match['alliance']['endgameRobot2'],$match['alliance']['endgameRobot3']);


    $offset = 0;
    $poscolorleft = "#444444";
    $poscolormiddle = "#444444";
    $poscolorright = "#444444";

    switch($mypos){
        case 0:
            //leave gray
            break;
        case 1:
            $poscolorleft = "#1ff258";
            break;
        case 2:
            $poscolormiddle = "#1ff258";
            break;
        case 3:
            $poscolorright = "#1ff258";
            break;
    }

    if($level != "IsLevel"){
        $offset = 10;
    }


    $barbonus = 0;

    print '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="100px" height="85px"  xml:space="preserve">
<style type="text/css">
	.st0park{display:none;fill:none;stroke:#888888;stroke-miterlimit:10;}
	.st1park{stroke:#000;stroke-miterlimit:10;}
	.st0hang{fill:none;stroke:#CCCCCC;stroke-miterlimit:10;}
	.st1hang{stroke:#000;stroke-miterlimit:10;}
	.bar{stroke:#000000;stroke-miterlimit:10;}
</style>';
    $points = 0;
    $mypoints = 0;


    switch($bot1){
        case "None":
            //leave gray
            break;
        case "Park":
            print '<line class="st0park" x1="20" y1="34.8" x2="20" y2="55.03"/>';
            print '<rect fill="'.$poscolorleft.'" x="6.68" y="55.03" class="st1park" width="26.64" height="12.16"/>';
            $points += 5;
            if ($mypos == 1){$mypoints += 5;}
            break;
        case "Hang":
            print '<line class="st0hang" x1="20" y1="'.(18 + $offset).'.8" x2="20" y2="'.(39 + $offset).'.03"/>';
            print '<rect fill="'.$poscolorleft.'" x="6.68" y="'.(39 + $offset).'.03" class="st1hang" width="26.64" height="12.16"/>';
            $points += 25;
            if ($mypos == 1){$mypoints += 25;}
            $barbonus = 1;
            break;
    }
    switch($bot2){
        case "None":
            //leave gray
            break;
        case "Park":
            print '<line class="st0park" x1="50" y1="34.8" x2="50" y2="55.03"/>';
            print '<rect fill="'.$poscolormiddle.'" x="36.68" y="55.03" class="st1park" width="26.64" height="12.16"/>';
            if ($mypos == 2){$mypoints += 5;}
            $points += 5;
            break;
        case "Hang":
            print '<line class="st0hang" x1="50" y1="18.8" x2="50" y2="39.03"/>';
            print '<rect fill="'.$poscolormiddle.'" x="36.68" y="'.(39).'.03" class="st1hang" width="26.64" height="12.16"/>';
            if ($mypos == 2){$mypoints += 25;}
            $points += 25;
            $barbonus = 1;
            break;
    }
    switch($bot3){
        case "None":
            //leave gray
            break;
        case "Park":
            print '<line class="st0park" x1="80" y1="34.8" x2="80" y2="55.03"/>';
            print '<rect fill="'.$poscolorright.'" x="66.68" y="55.03" class="st1park" width="26.64" height="12.16"/>';
            if ($mypos == 3){$mypoints += 5;}
            $points += 5;
            break;
        case "Hang":
            print '<line class="st0hang" x1="80" y1="'.(18 - $offset).'" x2="80" y2="'.(39 - $offset).'.03"/>';
            print '<rect fill="'.$poscolorright.'" x="66.68" y="'.(39 - $offset).'.03" class="st1hang" width="26.64" height="12.16"/>';
            if ($mypos == 3){$mypoints += 25;}
            $points += 25;
            $barbonus = 1;
            break;
    }
    if($level == "IsLevel"){
        if ($barbonus == 1){
            $bar = '<rect fill="#39B54A" x="5" y="15" class="bar" width="90" height="4"/>';
            $points += ($barbonus * 15);
        }
        else{
            $bar = '<rect fill="#444444" x="5" y="15" class="bar" width="90" height="4"/>';
        }


    }else{
        if ($barbonus == 1){
            $bar = '<rect x="5" y="15" fill="#B5394A" transform="matrix(0.95 -0.3123 0.3123 0.95 -2.8083 16.4658)" class="bar" width="90" height="4"/>';
        }
        else{
            $bar = '<rect x="5" y="15" fill="#444444" transform="matrix(0.95 -0.3123 0.3123 0.95 -2.8083 16.4658)" class="bar" width="90" height="4"/>';
        }
    }

    print $bar;
    print '<text text-anchor="start" x="2" y="81" fill="#efefef">Ali '.$points.'</text>';
    print '<text text-anchor="end" x="98" y="81" fill="#efefef">T '.$mypoints.'</text>';
    print '</svg> ';



    /* switch($stage){
		case "0"://0 Parked
            print '
	<rect fill="#444444" x="5" y="15" class="bar" width="90" height="4"/>
</svg> ';
			break;
		case "1"://1 Parked
            print '
	<line class="st0" x1="50" y1="34.8" x2="50" y2="55.03"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="55.03" class="st1" width="26.64" height="12.16"/>

	<rect fill="#444444" x="5" y="15" class="bar" width="90" height="4"/>
</svg> ';
			break;
		case "2"://2 Parked
            print '
	<line class="st0" x1="20" y1="34.8" x2="20" y2="55.03"/>
	<line class="st0" x1="80" y1="34.8" x2="80" y2="55.03"/>
	<rect fill="'.$poscolorright.'" x="66.68" y="55.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="55.03" class="st1" width="26.64" height="12.16"/>

	<rect fill="#444444" x="5" y="15" class="bar" width="90" height="4"/>
</svg> ';
			break;
		case "3"://3 Parked
            print '
	<line class="st0" x1="20" y1="34.8" x2="20" y2="55.03"/>
	<line class="st0" x1="50" y1="34.8" x2="50" y2="55.03"/>
	<line class="st0" x1="80" y1="34.8" x2="80" y2="55.03"/>
	<rect fill="'.$poscolorright.'" x="66.68" y="55.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="55.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="55.03" class="st1" width="26.64" height="12.16"/>

	<rect fill="#444444" x="5" y="15" class="bar" width="90" height="4"/>
</svg> ';
			break;
		case "4"://1 Level - 0 Parked
            print '
	<line class="st0" x1="50" y1="18.8" x2="50" y2="39.03"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="39.03" class="st1" width="26.64" height="12.16"/>

	<rect fill="#39B54A" x="5" y="15" class="st10" width="90" height="4"/>
</svg> ';
			break;
		case "5"://1 Level - 1 Parked
            print '

	<line class="st0" x1="20" y1="34.8" x2="20" y2="55.03"/>
	<rect fill="'.$poscolorright.'" x="6.68" y="55.03" class="st1" width="26.64" height="12.16"/>

	<line class="st0" x1="50" y1="18.8" x2="50" y2="39.03"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="39.03" class="st1" width="26.64" height="12.16"/>

    <rect fill="#39B54A" x="5" y="15" class="st10" width="90" height="4"/>
</svg> ';
			break;
		case "6"://1 Level - 2 Parked
            print '

<g id="Parked">
	<line class="st0" x1="20" y1="34.8" x2="20" y2="55.03"/>
	<line class="st0" x1="80" y1="34.8" x2="80" y2="55.03"/>
	<rect fill="'.$poscolorright.'" x="66.68" y="55.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="55.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Level">
	<line class="st0" x1="50" y1="18.8" x2="50" y2="39.03"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="39.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Bar">
	 <rect fill="#39B54A" x="5" y="15" class="st10" width="90" height="4"/>
</g>
</svg> ';
			break;
		case "7"://2 Level - 0 Parked
            print '


<g id="Level">
	<line class="st0" x1="20" y1="18.8" x2="20" y2="39.03"/>
	<line class="st0" x1="80" y1="18.8" x2="80" y2="39.03"/>
	<rect fill="'.$poscolorright.'" x="66.68" y="39.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="39.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Bar">
	<rect fill="#39B54A" x="5" y="15" class="st10" width="90" height="4"/>
</g>
</svg> ';
			break;
		case "8"://2 Level - 1 Parked
            print '

<g id="Parked">
	<line class="st0" x1="50" y1="34.8" x2="50" y2="55.03"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="55.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Level">
	<line class="st0" x1="20" y1="18.8" x2="20" y2="39.03"/>
	<line class="st0" x1="80" y1="18.8" x2="80" y2="39.03"/>
	<rect fill="'.$poscolorright.'" x="66.68" y="39.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="39.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Bar">
	<rect fill="#39B54A" x="5" y="15" class="st10" width="90" height="4"/>
</g>
</svg> ';
			break;
		case "9"://3 Level
            print '

<g id="Level">
	<line class="st0" x1="20" y1="18.8" x2="20" y2="39.03"/>
	<line class="st0" x1="50" y1="18.8" x2="50" y2="39.03"/>
	<line class="st0" x1="80" y1="18.8" x2="80" y2="39.03"/>
	<rect fill="'.$poscolorright.'" x="66.68" y="39.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="39.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="39.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Bar">
	<rect fill="#39B54A" x="5" y="15" class="st10" width="90" height="4"/>
</g>
</svg> ';
			break;
		case "10"://1 Not Level - 0 Parked
            print '
<g id="Not_Level">
	<line class="st0" x1="20" y1="28.8" x2="20" y2="49.03"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="49.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Bar">
	<rect x="5" y="15" fill="#B5394A" transform="matrix(0.95 -0.3123 0.3123 0.95 -2.8083 16.4658)" class="bar" width="90" height="4"/>
</g>
</svg> ';
			break;
		case "11"://1 Not Level - 1 Parked
            print '
<g id="Not_Level">
	<line class="st0" x1="20" y1="28.8" x2="20" y2="49.03"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="49.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Parked">
	<line class="st0" x1="80" y1="34.8" x2="80" y2="55.03"/>
	<rect fill="'.$poscolorright.'" x="66.68" y="55.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Bar">
	<rect x="5" y="15" fill="#B5394A" transform="matrix(0.95 -0.3123 0.3123 0.95 -2.8083 16.4658)" class="bar" width="90" height="4"/>
</g>
</svg> ';
			break;
		case "12"://1 Not Level - 2 Parked
            print '
<g id="Not_Level">
	<line class="st0" x1="20" y1="28.8" x2="20" y2="49.03"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="49.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Parked">
	<line class="st0" x1="50" y1="34.8" x2="50" y2="55.03"/>
	<line class="st0" x1="80" y1="34.8" x2="80" y2="55.03"/>
	<rect fill="'.$poscolorright.'" x="66.68" y="55.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="55.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Bar">
	<rect x="5" y="15" fill="#B5394A" transform="matrix(0.95 -0.3123 0.3123 0.95 -2.8083 16.4658)" class="bar" width="90" height="4"/>
</g>
</svg> ';
			break;
		case "13"://2 Not Level - 0 Parked
            print '
<g id="Not_Level">
	<line class="st0" x1="20" y1="28.8" x2="20" y2="49.03"/>
	<line class="st0" x1="50" y1="18.8" x2="50" y2="39.03"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="39.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="49.03" class="st1" width="26.64" height="12.16"/>
</g>

<g id="Bar">
	<rect x="5" y="15" fill="#B5394A" transform="matrix(0.95 -0.3123 0.3123 0.95 -2.8083 16.4658)" class="bar" width="90" height="4"/>
</g>
</svg> ';
			break;
		case "14"://2 Not Level - 1 Parked
            print '
<g id="Not_Level">
	<line class="st0" x1="20" y1="28.8" x2="20" y2="49.03"/>
	<line class="st0" x1="50" y1="18.8" x2="50" y2="39.03"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="39.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="49.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Parked">
	<line class="st0" x1="80" y1="34.8" x2="80" y2="55.03"/>
	<rect fill="'.$poscolorright.'" x="66.68" y="55.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Bar">
	<rect x="5" y="15" fill="#B5394A" transform="matrix(0.95 -0.3123 0.3123 0.95 -2.8083 16.4658)" class="bar" width="90" height="4"/>
</g>
</svg> ';
			break;
		case "15"://3 Not Level
            print '
<g id="Not_Level">
	<line class="st0" x1="20" y1="28.8" x2="20" y2="49.03"/>
	<line class="st0" x1="50" y1="18.8" x2="50" y2="39.03"/>
	<line class="st0" x1="80" y1="8.8" x2="80" y2="29.03"/>
	<rect fill="'.$poscolorright.'" x="66.68" y="29.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="39.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="49.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Bar">
	<rect x="5" y="15" fill="#B5394A" transform="matrix(0.95 -0.3123 0.3123 0.95 -2.8083 16.4658)" class="bar" width="90" height="4"/>
</g>
</svg> ';
			break;

		default:

			break;
	}

*/
}
function gfx_defencerecieved($level){
    print '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="20px" height="85px"  xml:space="preserve">
<style type="text/css">
	.defensecolor{fill:#FFBC64;stroke:#F7931E;stroke-miterlimit:10;}
	.defensenocolor{fill:#333333;stroke:#666666;stroke-miterlimit:10;}
	.defensena{fill:#222222;stroke:#333333;stroke-miterlimit:10;}
</style>';
    switch($level){
        case "1":
            print '
            <polygon class="defensena" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensena" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensena" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensena" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensena" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Rec</text></svg>';
            break;
        case "2":
            print '
             <polygon class="defensenocolor" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensenocolor" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensenocolor" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensenocolor" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensecolor" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Rec</text></svg>';
            break;
        case "3":
            print '
             <polygon class="defensenocolor" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensenocolor" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensenocolor" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensecolor" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensecolor" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Rec</text></svg>';
            break;
        case "4":
            print '
             <polygon class="defensenocolor" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensenocolor" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensecolor" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensecolor" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensecolor" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Rec</text></svg>';
            break;
        case "5":
            print '
             <polygon class="defensenocolor" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensecolor" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensecolor" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensecolor" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensecolor" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Rec</text></svg>';
            break;
        case "6":
            print '
             <polygon class="defensecolor" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensecolor" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensecolor" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensecolor" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensecolor" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Rec</text></svg>';
            break;
    }

}
function gfx_defencegiven($level){
    print '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="20px" height="85px"  xml:space="preserve">
<style type="text/css">
	.defensecolor{fill:#FFBC64;stroke:#F7931E;stroke-miterlimit:10;}
	.defensenocolor{fill:#333333;stroke:#666666;stroke-miterlimit:10;}
	.defensena{fill:#222222;stroke:#333333;stroke-miterlimit:10;}
</style>';
    switch($level){
        case "1":
            print '
            <polygon class="defensena" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensena" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensena" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensena" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensena" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Giv</text></svg>';
            break;
        case "2":
            print '
             <polygon class="defensenocolor" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensenocolor" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensenocolor" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensenocolor" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensecolor" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Giv</text></svg>';
            break;
        case "3":
            print '
             <polygon class="defensenocolor" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensenocolor" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensenocolor" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensecolor" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensecolor" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Giv</text></svg>';
            break;
        case "4":
            print '
             <polygon class="defensenocolor" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensenocolor" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensecolor" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensecolor" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensecolor" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Giv</text></svg>';
            break;
        case "5":
            print '
             <polygon class="defensenocolor" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensecolor" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensecolor" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensecolor" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensecolor" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Giv</text></svg>';
            break;
        case "6":
            print '
             <polygon class="defensecolor" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensecolor" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensecolor" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensecolor" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensecolor" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Giv</text></svg>';
            break;
    }

}