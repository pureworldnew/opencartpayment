<?php
/**
 * Numbers_Words
 *
 * PHP version 5
 *
 * Copyright (c) 1997-2006 The PHP Group
 *
 * This source file is subject to version 3.01 of the PHP license,
 * that is bundled with this package in the file LICENSE, and is
 * available at through the world-wide-web at
 * http://www.php.net/license/3_01.txt
 * If you did not receive a copy of the PHP license and are unable to
 * obtain it through the world-wide-web, please send a note to
 * license@php.net so we can mail you a copy immediately.
 *
 * @category Numbers
 * @package  Numbers_Words
 * @author   Filippo Beltramini <phil@esight.it>
 * @author   Davide Caironi     <cairo@esight.it>
 * @license  PHP 3.01 http://www.php.net/license/3_01.txt
 * @version  SVN: $Id$
 * @link     http://pear.php.net/package/Numbers_Words
 */

/**
 * Class for translating numbers into Italian.
 *
 * @author Filippo Beltramini <phil@esight.it>
 * @author Davide Caironi     <cairo@esight.it>
 * @package Numbers_Words
 */

/**
 * Include needed files
 */
// require_once "Numbers/Words.php";

/**
 * Class for translating numbers into Italian.
 * It supports up to quadrilions
 *
 * @category Numbers
 * @package  Numbers_Words
 * @author   Filippo Beltramini <phil@esight.it>
 * @author   Davide Caironi     <cairo@esight.it>
 * @license  PHP 3.01 http://www.php.net/license/3_01.txt
 * @link     http://pear.php.net/package/Numbers_Words
 */

class Numbers_Words_Locale_it extends Numbers_Words
{
    // {{{ properties

    /**
     * Locale name
     * @var string
     * @access public
     */
    var $locale = 'it_IT';

    /**
     * Language name in English
     * @var string
     * @access public
     */
    var $lang = 'Italian';

    /**
     * Native language name
     * @var string
     * @access public
     */
    var $lang_native = 'Italiano';

    /**
     * The word for the minus sign
     * @var string
     * @access private
     */
    var $_minus = 'meno ';

    /**
     * The sufixes for exponents (singular and plural)
     * @var array
     * @access private
     */
    var $_exponent = array(
        0 => array('',''),
        3 => array('mille','mila'),
        6 => array('milione','miloni'),
       12 => array('miliardo','miliardi'),
       18 => array('trillone','trilloni'),
       24 => array('quadrilione','quadrilioni'),
        );
        
        
         /**
     * The currency names (based on the below links,
     * informations from central bank websites and on encyclopedias)
     *
     * @var array
     * @link http://30-03-67.dreamstation.com/currency_alfa.htm World Currency Information
     * @link http://www.jhall.demon.co.uk/currency/by_abbrev.html World currencies
     * @link http://www.shoestring.co.kr/world/p.visa/change.htm Currency names in English
     * @access private
     */
    var $_currency_names = array(
      'ALL' => array(array('lek'), array('qindarka')),
      'AUD' => array(array('Australian dollar'), array('cent')),
      'BAM' => array(array('convertible marka'), array('fenig')),
      'BGN' => array(array('lev'), array('stotinka')),
      'BRL' => array(array('real'), array('centavos')),
      'BYR' => array(array('Belarussian rouble'), array('kopiejka')),
      'CAD' => array(array('Canadian dollar'), array('cent')),
      'CHF' => array(array('Swiss franc'), array('rapp')),
      'CYP' => array(array('Cypriot pound'), array('cent')),
      'CZK' => array(array('Czech koruna'), array('halerz')),
      'DKK' => array(array('Danish krone'), array('ore')),
      'EEK' => array(array('kroon'), array('senti')),
      'EUR' => array(array('euro'), array('cent')),
      'GBP' => array(array('pound', 'pounds'), array('pence', 'pence')),
      'HKD' => array(array('Hong Kong dollar'), array('cent')),
      'HRK' => array(array('Croatian kuna'), array('lipa')),
      'HUF' => array(array('forint'), array('filler')),
      'ILS' => array(array('new sheqel','new sheqels'), array('agora','agorot')),
      'ISK' => array(array('Icelandic króna'), array('aurar')),
      'JPY' => array(array('yen'), array('sen')),
      'LTL' => array(array('litas'), array('cent')),
      'LVL' => array(array('lat'), array('sentim')),
      'MKD' => array(array('Macedonian dinar'), array('deni')),
      'MTL' => array(array('Maltese lira'), array('centym')),
      'NOK' => array(array('Norwegian krone'), array('oere')),
      'PLN' => array(array('zloty', 'zlotys'), array('grosz')),
      'ROL' => array(array('Romanian leu'), array('bani')),
      'RUB' => array(array('Russian Federation rouble'), array('kopiejka')),
      'SEK' => array(array('Swedish krona'), array('oere')),
      'SIT' => array(array('Tolar'), array('stotinia')),
      'SKK' => array(array('Slovak koruna'), array()),
      'TRL' => array(array('lira'), array('kuruþ')),
      'UAH' => array(array('hryvna'), array('cent')),
      'USD' => array(array('dollar'), array('cent')),
      'YUM' => array(array('dinars'), array('para')),
      'ZAR' => array(array('rand'), array('cent'))
    );

    /**
     * The default currency name
     * @var string
     * @access public
     */
    var $def_currency = 'EUR';
    
    
    
    /**
     * The array containing the digits (indexed by the digits themselves).
     * @var array
     * @access private
     */
    var $_digits = array(
      0 => 'zero', 'uno', 'due', 'tre', 'quattro',
       'cinque', 'sei', 'sette', 'otto', 'nove'
    );

    /**
     * The word separator
     * @var string
     * @access private
     */
    var $_sep = '';
    // }}}
    // {{{ _toWords()
    /**
     * Converts a number to its word representation
     * in italiano.
     *
     * @param integer $num   An integer between -infinity and infinity inclusive :)
     *                        that should be converted to a words representation
     * @param integer $power The power of ten for the rest of the number to the right.
     *                        For example toWords(12,3) should give "doce mil".
     *                        Optional, defaults to 0.
     *
     * @return string  The corresponding word representation
     *
     * @access protected
     * @author Filippo Beltramini
     * @since  Numbers_Words 0.16.3
     */
    function _toWords($num, $power = 0)
    {
        // The return string;
        $ret = '';

        // add a the word for the minus sign if necessary
        if (substr($num, 0, 1) == '-') {
            $ret = $this->_sep . $this->_minus;
            $num = substr($num, 1);
        }


        // strip excessive zero signs
        $num = preg_replace('/^0+/', '', $num);

        if (strlen($num) > 6) {
            $current_power = 6;
            // check for highest power
            if (isset($this->_exponent[$power])) {
                // convert the number above the first 6 digits
                // with it's corresponding $power.
                $snum = substr($num, 0, -6);
                $snum = preg_replace('/^0+/', '', $snum);
                if ($snum !== '') {
                    $ret .= $this->_toWords($snum, $power + 6);
                }
            }
            $num = substr($num, -6);
            if ($num == 0) {
                return $ret;
            }
        } elseif ($num == 0 || $num == '') {
            return(' '.$this->_digits[0].' ');
            $current_power = strlen($num);
        } else {
            $current_power = strlen($num);
        }

        // See if we need "thousands"
        $thousands = floor($num / 1000);
        if ($thousands == 1) {
            $ret .= $this->_sep . 'mille' . $this->_sep;
        } elseif ($thousands > 1) {
            $ret .= $this->_toWords($thousands, 3) . $this->_sep;//. 'mil' . $this->_sep;
        }

        // values for digits, tens and hundreds
        $h = floor(($num / 100) % 10);
        $t = floor(($num / 10) % 10);
        $d = floor($num % 10);

        // centinaia: duecento, trecento, etc...
        switch ($h) {
        case 1:
            if (($d == 0) and ($t == 0)) { // is it's '100' use 'cien'
                $ret .= $this->_sep . 'cento';
            } else {
                $ret .= $this->_sep . 'cento';
            }
            break;
        case 2:
        case 3:
        case 4:
        case 6:
        case 8:
            $ret .= $this->_sep . $this->_digits[$h] . 'cento';
            break;
        case 5:
            $ret .= $this->_sep . 'cinquecento';
            break;
        case 7:
            $ret .= $this->_sep . 'settecento';
            break;
        case 9:
            $ret .= $this->_sep . 'novecento';
            break;
        }

        // decine: venti trenta, etc...
        switch ($t) {         
        case 9:
            switch ($d){
            case 1: 
            case 8:
                $ret .= $this->_sep . 'novant' ; 
                break;
            default:
                $ret .= $this->_sep . 'novanta' ; 
                break;
            }
       
            break;  
        case 8:
            switch ($d){
            case 1: 
            case 8:
                $ret .= $this->_sep . 'ottant' ; 
                break;
            default:
                $ret .= $this->_sep . 'ottanta' ; 
                break;
            }
       
            break;  
        case 7:
            switch ($d){
            case 1: 
            case 8:
                $ret .= $this->_sep . 'settant' ; 
                break;
            default:
                $ret .= $this->_sep . 'settanta' ; 
                break;
            }
            break;            
        case 6:
            switch ($d){
            case 1: 
            case 8:
                $ret .= $this->_sep . 'sessant' ; 
                break;
            default:
                $ret .= $this->_sep . 'sessanta' ; 
                break;
            }       
            break;
        case 5:
            switch ($d){
            case 1: 
            case 8:
                $ret .= $this->_sep . 'cinquant' ; 
                break;
            default:
                $ret .= $this->_sep . 'cinquanta' ; 
                break;
            }
            break;
        case 4:
            switch ($d){
            case 1: 
            case 8:
                $ret .= $this->_sep . 'quarant' ; 
                break;
            default:
                $ret .= $this->_sep . 'quaranta' ; 
                break;
            }
            break;
        case 3:
            switch ($d){    
            case 1:   
            case 8:
                $ret .= $this->_sep . 'trent' ;
                break;
            default:
                $ret .= $this->_sep . 'trenta' ;
                break;
            }
            break;
        case 2:
            switch ($d){
            case 0:
                $ret .= $this->_sep . 'venti';
                break;
            case 1: 
            case 8:
                $ret .= $this->_sep . 'vent' . $this->_digits[$d];
                break;
            default:
                $ret .= $this->_sep . 'venti'  . $this->_digits[$d];
                break;
            }
               
            
            break;

        case 1:
            switch ($d) {
            case 0:
                $ret .= $this->_sep . 'dieci';
                break;

            case 1:
                $ret .= $this->_sep . 'undici';
                break;

            case 2:
                $ret .= $this->_sep . 'dodici';
                break;

            case 3:
                $ret .= $this->_sep . 'tredici';
                break;

            case 4:
                $ret .= $this->_sep . 'quattordici';
                break;

            case 5:
                $ret .= $this->_sep . 'quindici';
                break;

            case 6:
                 $ret .= $this->_sep . 'sedici';
                break;

            case 7:
                 $ret .= $this->_sep . 'diciassette';
                break;

            case 8:
                $ret .= $this->_sep . 'diciotto';
                break;

            case 9:
                $ret .= $this->_sep . 'diciannove';
                break;
            }
            break;
        }

        // add digits only if it is a multiple of 10 and not 1x or 2x
        if (($t != 1) and ($t != 2) and ($d > 0)) {
             // don't add 'e' for numbers below 10
            if ($t != 0) {
                // use 'un' instead of 'uno' when there is a suffix ('mila', 'milloni', etc...)
                if (($power > 0) and ($d == 1)) {
                    $ret .= $this->_sep.' e un';
                } else {
                    $ret .= $this->_sep.''.$this->_digits[$d];
                }
            } else {
                if (($power > 0) and ($d == 1)) {
                    $ret .= $this->_sep.'un ';
                } else {
                    $ret .= $this->_sep.$this->_digits[$d];
                }
            }
        }

        if ($power > 0) {
            if (isset($this->_exponent[$power])) {
                $lev = $this->_exponent[$power];
            }

            if (!isset($lev) || !is_array($lev)) {
                return null;
            }

            // if it's only one use the singular suffix
            if (($d == 1) and ($t == 0) and ($h == 0)) {
                $suffix = $lev[0];
            } else {
                $suffix = $lev[1];
            }
            if ($num != 0) {
                $ret .= $this->_sep . $suffix;
            }
        }

        return $ret;
    }
    // }}}
    
            // {{{ toCurrencyWords()

    /**
     * Converts a currency value to its word representation
     * (with monetary units) in English language
     *
     * @param integer $int_curr         An international currency symbol
     *                                  as defined by the ISO 4217 standard (three characters)
     * @param integer $decimal          A money total amount without fraction part (e.g. amount of dollars)
     * @param integer $fraction         Fractional part of the money amount (e.g. amount of cents)
     *                                  Optional. Defaults to false.
     * @param integer $convert_fraction Convert fraction to words (left as numeric if set to false).
     *                                  Optional. Defaults to true.
     *
     * @return string  The corresponding word representation for the currency
     *
     * @access public
     * @author Piotr Klaban <makler@man.torun.pl>
     * @since  Numbers_Words 0.4
     */
    function toCurrencyWords($int_curr, $decimal, $fraction = false, $convert_fraction = false)
    {
        $int_curr = strtoupper($int_curr);
        if (!isset($this->_currency_names[$int_curr])) {
            $int_curr = $this->def_currency;
        }
        $curr_names = $this->_currency_names[$int_curr];

        $ret = trim($this->_toWords($decimal));
        $lev = ($decimal == 1) ? 0 : 1;
        if ($lev > 0) {
            if (count($curr_names[0]) > 1) {
                $ret .= ' '.$this->_sep . $curr_names[0][$lev].' ';
            } else {
                $ret .= ' '.$this->_sep . $curr_names[0][0] . 's ';
            }
        } else {
            $ret .= $this->_sep . $curr_names[0][0];
        }

        if ($fraction !== false) {
            if ($convert_fraction) {
                $ret .= $this->_sep . trim($this->_toWords($fraction));
            } else {
                $ret .= $this->_sep . $fraction;
            }
            $lev = ($fraction == 1) ? 0 : 1;
            if ($lev > 0) {
                if (count($curr_names[1]) > 1) {
                    $ret .= ' '.$this->_sep . $curr_names[1][$lev].' ';
                } else {
                    $ret .= ' '.$this->_sep . $curr_names[1][0] . 's';
                }
            } else {
                $ret .= $this->_sep . $curr_names[1][0];
            }
        }
        return $ret;
    }
    // }}}
}
