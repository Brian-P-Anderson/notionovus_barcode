<?php
/*
Plugin Name: notionovus_barcode
Contributors: @notionovus
Plugin URI: bit.ly/2p954VL
Description: This plugin puts barcodes on the page
Author: Brian Anderson
Version: 0.1
Author URI: notionovus.com
Donate link: https://www.rotary.org/give
Tags: barcode, Code 128B
Requires at least: 4.9
Tested up to: 4.9
Stable tag: 4.9
Requires PHP: 7
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Plugin Name: Notionovus Barcode Shortcode
*/

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
        exit;
}

// Generic arrays for drawing 5-bit graphics. Building blocks for all barcode symbologies
// Painstakingly derived gobblety-goop, but essentially the two middle sections of image data unique to each graphic
function genBarcode($inputString,$intWidth,$intHeight) {

$array5bit_A = array ( 'f//AAAAAAAAAAAAAAAAAAAA', 'f//AAAAAAAAAAAAAAAAAAAB', 'f//AAAAAAAAAAAAAAEAAAD/',
 'f//AAAAAAAAAAAAAAEAAAAA', 'f//AAAAAAAAAQAAAP8AAAAA', 'f//AAAAAAAAAQAAAP8AAAAB', 'f//AAAAAAAAAQAAAAAAAAD/',
 'f//AAAAAAAAAQAAAAAAAAAA', 'f//AAABAAAA/wAAAAAAAAAA', 'f//AAABAAAA/wAAAAAAAAAB', 'f//AAABAAAA/wAAAAEAAAD/',
 'f//AAABAAAA/wAAAAEAAAAA', 'f//AAABAAAAAAAAAP8AAAAA', 'f//AAABAAAAAAAAAP8AAAAB', 'f//AAABAAAAAAAAAAAAAAD/',
 'f//AAABAAAAAAAAAAAAAAAA', 'QD/AAD/AAAAAAAAAAAAAAAA', 'QD/AAD/AAAAAAAAAAAAAAAB', 'QD/AAD/AAAAAAAAAAEAAAD/',
 'QD/AAD/AAAAAAAAAAEAAAAA', 'QD/AAD/AAAAAQAAAP8AAAAA', 'QD/AAD/AAAAAQAAAP8AAAAB', 'QD/AAD/AAAAAQAAAAAAAAD/',
 'QD/AAD/AAAAAQAAAAAAAAAA', 'QD/AAAAAAAA/wAAAAAAAAAA', 'QD/AAAAAAAA/wAAAAAAAAAB', 'SL/AADeAAAA/gAAAAIAAAD+',
 'QD/AAAAAAAA/wAAAAEAAAAA', 'QD/AAAAAAAAAAAAAP8AAAAA', 'QD/AAAAAAAAAAAAAP8AAAAB', 'QD/AAAAAAAAAAAAAAAAAAD/',
 'QD/AAAAAAAAAAAAAAAAAAAA');
$array5bit_B = array ( 'US0CAuSD38g', 'UUYCA7QBErs', 'ajEDAm49ReY', 'UUoCA+juogg', 'bjEDAjQrOn0', 'bkoDA3iPVH4',
 'ajUDAt82atY', 'UU4CA1nljTg', 'cjEDAghkmFU', 'ckoDA0TA9lY', 'izUEAhrxcbg', 'ck4DAxY8F10', 'bjUDAlvFFR8', 'bk4DAxdhexw',
 'ajkDAr7LFAw', 'UVICAyQ+UJI', 'TTECAq7UnEM', 'TUoCA+Jw8kA', 'ZjUDAmZGozo', 'TU4CA7CME0s', 'ajUDAvnk9E4', 'ak4DA7VAmk0',
 'ZjkDAtle3bI', 'TVICAxOyzrM', 'STUCAqHeHtM', 'SU4CA+16cNA', 'h6QEAZKdo54', 'SVICA62zYxM', 'RTkCAqx1lb4', 'RVICA/z3WM0',
 'QT0CAkdoxRU', 'KFYBA46vJCA');

// Painstakingly derived gobblety-goop, but essentially the front, back and mid-matter common to all barcode images...
$stringStart = '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAACCAQAAADLaIVbAAAANUlEQVQIHQEqANX/A';
$stringMid = 'AAAAAgAAAAAAAAAAAAAAAAAAAAAAAAAA';
$stringEnd = 'AAAAASUVORK5CYII=" style="height:';

// Input is a long string of 1's and 0's, output is the HTML <img> stack
// Pads to the last character to ensure length is divisible by 5
 $intInputlen = strlen($inputString);
 $intRawmod = $intInputlen % 5; // Modulo 5 remainder
 if ($intRawmod > 0)
   for ($i = 0; $i < (5 - $intRawmod); $i++)
     $inputString .= "0"; // If not evenly divisible, pad with zeroes

 $intChunks = strlen($inputString) / 5; // Create array for as many chunks as are now in input string
 $arraySeq = array (); // Create array for as many chunks as are now in input string


// Converts string of 1's and 0's to integer array
 for ($i = 0; $i < $intChunks; $i++) {
   $intSeq = intval(substr($inputString, $i * 5, 5), 2);
   $arraySeq[$i] = $intSeq;
 }

// Takes integer array and converts to "<img ...>" graphics for display
 $intArraylen = $i;
 $resultString = "";
 for ($i = 0; $i < $intArraylen; $i++) {
   $resultString .= $stringStart;
   $resultString .= $array5bit_A[$arraySeq[$i]];
   $resultString .= $stringMid;
   $resultString .= $array5bit_B[$arraySeq[$i]];
   $resultString .= $stringEnd;
   $resultString .= $intHeight;
   $resultString .= 'px;width:';
   $resultString .= $intWidth;
   $resultString .= 'px;" >';
 }
 return $resultString;
}

function gen128B_onesandzeroes($stringConvert) {
// Code 128 Specific Array

$arrayCode128Bin = array ( '11011001100', '11001101100', '11001100110', '10010011000', '10010001100', '10001001100',
 '10011001000', '10011000100', '10001100100', '11001001000', '11001000100', '11000100100', '10110011100', '10011011100',
 '10011001110', '10111001100', '10011101100', '10011100110', '11001110010', '11001011100', '11001001110', '11011100100',
 '11001110100', '11101101110', '11101001100', '11100101100', '11100100110', '11101100100', '11100110100', '11100110010',
 '11011011000', '11011000110', '11000110110', '10100011000', '10001011000', '10001000110', '10110001000', '10001101000',
 '10001100010', '11010001000', '11000101000', '11000100010', '10110111000', '10110001110', '10001101110', '10111011000',
 '10111000110', '10001110110', '11101110110', '11010001110', '11000101110', '11011101000', '11011100010', '11011101110',
 '11101011000', '11101000110', '11100010110', '11101101000', '11101100010', '11100011010', '11101111010', '11001000010',
 '11110001010', '10100110000', '10100001100', '10010110000', '10010000110', '10000101100', '10000100110', '10110010000',
 '10110000100', '10011010000', '10011000010', '10000110100', '10000110010', '11000010010', '11001010000', '11110111010',
 '11000010100', '10001111010', '10100111100', '10010111100', '10010011110', '10111100100', '10011110100', '10011110010',
 '11110100100', '11110010100', '11110010010', '11011011110', '11011110110', '11110110110', '10101111000', '10100011110',
 '10001011110', '10111101000', '10111100010', '11110101000', '11110100010', '10111011110', '10111101110', '11101011110',
 '11110101110', '11010000100', '11010010000', '11010011100', '1100011101011', '11010111000');

 $intLength = strlen($stringConvert);
 $arrayData[0] = 104;
 $intWtProd = 104;
 $arrayData[$intLength + 2] = 106;
 for ($i = 0; $i < $intLength; $i++) {
        $arrayData[$i + 1] = ord(substr($stringConvert, $i, 1)) - 32;
        $intWeight = $i + 1;
        $intWtProd += $intWeight * $arrayData[$i + 1];
 }
 $arrayData[$intLength + 1] = $intWtProd % 103;
// Converts Code 128B array into string of 1's and 0's
 $strRaw = "0000";
 for ($i = 0; $i < ($intLength + 4); $i++) {
  $strRaw .= $arrayCode128Bin[$arrayData[$i]];
 }
 $strRaw .= "00000";
 return $strRaw;
}

function shortcode_notionovus_barcode( $atts ) {
        // Verify Attributes
        // define shortcode variables
        extract( shortcode_atts( array(
                'str' => 'Testing123',
                'h' => 50,
                'w' => 6,
        ), $atts ) );

        // begin output variable
        $output = '<div class="notionovus_barcode" style="display:inline-block">';
        $output .= genBarcode(gen128B_onesandzeroes($str), $w, $h);

        // complete output variable
        $output .= '</div>';

        // return output
        return $output;
}
