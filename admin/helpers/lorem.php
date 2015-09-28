<?php
/**
 * @version    $Id$
 * @package    JSN_PageBuilder
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file.
defined('_JEXEC') || die('Restricted access');

/**
 * Helper for render lorem text
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPagebuilderHelpersLorem {

	/**
	 * 	Copyright (c) 2009, Mathew Tinsley ( tinsley@tinsology.net )
	 * 	All rights reserved.
	 *
	 * 	Redistribution and use in source and binary forms, with or without
	 * 	modification, are permitted provided that the following conditions are met:
	 * 		* Redistributions of source code must retain the above copyright
	 * 		  notice, this list of conditions and the following disclaimer.
	 * 		* Redistributions in binary form must reproduce the above copyright
	 * 		  notice, this list of conditions and the following disclaimer in the
	 * 		  documentation and/or other materials provided with the distribution.
	 * 		* Neither the name of the organization nor the
	 * 		  names of its contributors may be used to endorse or promote products
	 * 		  derived from this software without specific prior written permission.
	 *
	 * 	THIS SOFTWARE IS PROVIDED BY MATHEW TINSLEY ''AS IS'' AND ANY
	 * 	EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
	 * 	WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
	 * 	DISCLAIMED. IN NO EVENT SHALL <copyright holder> BE LIABLE FOR ANY
	 * 	DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
	 * 	( INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
	 * 	LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION ) HOWEVER CAUSED AND
	 * 	ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
	 * 	( INCLUDING NEGLIGENCE OR OTHERWISE ) ARISING IN ANY WAY OUT OF THE USE OF THIS
	 * 	SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
	 */
	private $words, $wordsPerParagraph, $wordsPerSentence;

	/**
	 * Constructor
	 * 
	 * @param number $wordsPer
	 * 
	 * @return void
	 */
	function __construct( $wordsPer = 100 ) {
		$this->wordsPerParagraph = $wordsPer;
		$this->wordsPerSentence  = 24.460;
		$this->words = array(
			'lorem',
			'ipsum',
			'dolor',
			'sit',
			'amet',
			'consectetur',
			'adipiscing',
			'elit',
			'curabitur',
			'vel',
			'hendrerit',
			'libero',
			'eleifend',
			'blandit',
			'nunc',
			'ornare',
			'odio',
			'ut',
			'orci',
			'gravida',
			'imperdiet',
			'nullam',
			'purus',
			'lacinia',
			'a',
			'pretium',
			'quis',
			'congue',
			'praesent',
			'sagittis',
			'laoreet',
			'auctor',
			'mauris',
			'non',
			'velit',
			'eros',
			'dictum',
			'proin',
			'accumsan',
			'sapien',
			'nec',
			'massa',
			'volutpat',
			'venenatis',
			'sed',
			'eu',
			'molestie',
			'lacus',
			'quisque',
			'porttitor',
			'ligula',
			'dui',
			'mollis',
			'tempus',
			'at',
			'magna',
			'vestibulum',
			'turpis',
			'ac',
			'diam',
			'tincidunt',
			'id',
			'condimentum',
			'enim',
			'sodales',
			'in',
			'hac',
			'habitasse',
			'platea',
			'dictumst',
			'aenean',
			'neque',
			'fusce',
			'augue',
			'leo',
			'eget',
			'semper',
			'mattis',
			'tortor',
			'scelerisque',
			'nulla',
			'interdum',
			'tellus',
			'malesuada',
			'rhoncus',
			'porta',
			'sem',
			'aliquet',
			'et',
			'nam',
			'suspendisse',
			'potenti',
			'vivamus',
			'luctus',
			'fringilla',
			'erat',
			'donec',
			'justo',
			'vehicula',
			'ultricies',
			'varius',
			'ante',
			'primis',
			'faucibus',
			'ultrices',
			'posuere',
			'cubilia',
			'curae',
			'etiam',
			'cursus',
			'aliquam',
			'quam',
			'dapibus',
			'nisl',
			'feugiat',
			'egestas',
			'class',
			'aptent',
			'taciti',
			'sociosqu',
			'ad',
			'litora',
			'torquent',
			'per',
			'conubia',
			'nostra',
			'inceptos',
			'himenaeos',
			'phasellus',
			'nibh',
			'pulvinar',
			'vitae',
			'urna',
			'iaculis',
			'lobortis',
			'nisi',
			'viverra',
			'arcu',
			'morbi',
			'pellentesque',
			'metus',
			'commodo',
			'ut',
			'facilisis',
			'felis',
			'tristique',
			'ullamcorper',
			'placerat',
			'aenean',
			'convallis',
			'sollicitudin',
			'integer',
			'rutrum',
			'duis',
			'est',
			'etiam',
			'bibendum',
			'donec',
			'pharetra',
			'vulputate',
			'maecenas',
			'mi',
			'fermentum',
			'consequat',
			'suscipit',
			'aliquam',
			'habitant',
			'senectus',
			'netus',
			'fames',
			'quisque',
			'euismod',
			'curabitur',
			'lectus',
			'elementum',
			'tempor',
			'risus',
			'cras',					
		);
	}

	/**
	 * Get content 
	 * 
	 * @param unknown $count
	 * @param string $format
	 * @param string $loremipsum
	 * 
	 * @return string|Ambigous <string, multitype:multitype:Ambigous <>  >
	 */
	function get_content( $count, $format = 'html', $loremipsum = true ) {
		$format = strtolower( $format );

		if ( $count <= 0 )
			return '';

		switch ( $format ) {
			case 'txt':
				return $this->get_text( $count, $loremipsum );
			case 'plain':
				return $this->get_plain( $count, $loremipsum );
			default:
				return $this->get_html( $count, $loremipsum );
		}
	}

	/**
	 * Get words
	 * 
	 * @param array $arr
	 * @param number $count
	 * @param string $loremipsum
	 * 
	 * @return void
	 */
	function get_words( &$arr, $count, $loremipsum ) {
		$i = 0;
		if ( $loremipsum ) {
			$i = 2;
			$arr[0] = 'lorem';
			$arr[1] = 'ipsum';
		}

		for ( $i; $i < $count; $i++ ) {
			$index = array_rand( $this->words );
			$word  = $this->words[$index];
			//echo $index . '=>' . $word . '<br />';

			if ( $i > 0 && $arr[$i - 1] == $word )
				$i--;
			else
				$arr[$i] = $word;
		}
	}

	/**
	 * Get plain text
	 * 
	 * @param number $count
	 * @param string $loremipsum
	 * @param string $returnStr
	 * 
	 * @return string|multitype:multitype:Ambigous <>
	 */
	function get_plain( $count, $loremipsum, $returnStr = true ) {
		$words = array();
		$this->get_words( $words, $count, $loremipsum );
		//print_r( $words );

		$delta     = $count;
		$curr  = 0;
		$sentences = array();
		while ( $delta > 0 ) {
			$senSize = $this->gaussian_sentence();
			//echo $curr . '<br />';
			if ( ($delta - $senSize ) < 4)
				$senSize = $delta;

			$delta -= $senSize;

			$sentence = array();
			for ( $i = $curr; $i < ($curr + $senSize ); $i++)
				$sentence[] = $words[$i];

			$this->punctuate( $sentence );
			$curr = $curr + $senSize;
			$sentences[] = $sentence;
		}

		if ( $returnStr ) {
			$output = '';
			foreach ( $sentences as $s )
				foreach ( $s as $w )
					$output .= $w . ' ';

			return $output;
		}
		else
			return $sentences;
	}

	/**
	 * Get text
	 * 
	 * @param number $count
	 * @param string $loremipsum
	 * 
	 * @return string
	 */
	function get_text( $count, $loremipsum ) {
		$sentences  = $this->get_plain( $count, $loremipsum, false );
		$paragraphs = $this->get_paragraph_arr( $sentences );

		$paragraphStr = array();
		foreach ( $paragraphs as $p ) {
			$paragraphStr[] = $this->paragraph_to_string( $p );
		}

		$paragraphStr[0] = "\t" . $paragraphStr[0];
		return implode( "\n\n\t", $paragraphStr );
	}

	/**
	 * Get paragraph array
	 * 
	 * @param array $sentences
	 * 
	 * @return array
	 */
	function get_paragraph_arr( $sentences ) {
		$wordsPer    = $this->wordsPerParagraph;
		$sentenceAvg = $this->wordsPerSentence;
		$total = count( $sentences );

		$paragraphs = array();
		$pCount     = 0;
		$currCount  = 0;
		$curr = array();

		for ( $i = 0; $i < $total; $i++ ) {
			$s = $sentences[$i];
			$currCount += count( $s );
			$curr[]     = $s;
			if ( $currCount >= ( $wordsPer - round( $sentenceAvg / 2.00 ) ) || $i == $total - 1 ) {
				$currCount    = 0;
				$paragraphs[] = $curr;
				$curr = array();
				//print_r( $paragraphs );
			}
			//print_r( $paragraphs );
		}

		return $paragraphs;
	}

	/**
	 * Get lorem text with html format
	 * 
	 * @param number $count
	 * @param string $loremipsum
	 * 
	 * @return string
	 */
	function get_html( $count, $loremipsum ) {
		$sentences  = $this->get_plain( $count, $loremipsum, false );
		$paragraphs = $this->get_paragraph_arr( $sentences );
		//print_r( $paragraphs );

		$paragraphStr = array();
		foreach ( $paragraphs as $p ) {
			$paragraphStr[] = "<p>\n" . $this->paragraph_to_string( $p, true ) . '</p>';
		}

		//add new lines for the sake of clean code
		return implode( "\n", $paragraphStr );
	}

	/**
	 * Convert paragraph to string
	 * 
	 * @param array $paragraph
	 * @param string $htmlCleanCode
	 * 
	 * @return string
	 */
	function paragraph_to_string( $paragraph, $htmlCleanCode = false ) {
		$paragraphStr = '';
		foreach ( $paragraph as $sentence ) {
			foreach ( $sentence as $word )
				$paragraphStr .= $word . ' ';

			if ( $htmlCleanCode )
				$paragraphStr .= "\n";
		}
		return $paragraphStr;
	}

	/**
	 * Inserts commas and periods in the given
	 * word array.
	 * 
	 * @param array $sentence
	 * 
	 * @return string
	 */
	function punctuate( & $sentence ) {
		$count = count( $sentence );
		$sentence[$count - 1] = $sentence[$count - 1] . '.';

		if ( $count < 4 )
			return $sentence;

		$commas = $this->number_of_commas( $count );

		for ( $i = 1; $i <= $commas; $i++ ) {
			$index = ( int ) round( $i * $count / ( $commas + 1 ) );

			if ( $index < ($count - 1 ) && $index > 0 ) {
				$sentence[$index] = $sentence[$index] . ',';
			}
		}
	}

	/**
	 * Determines the number of commas for a
	 * sentence of the given length. Average and
	 * standard deviation are determined superficially
	 * 
	 * @param number $len
	 * 
	 * @return number
	 */
	function number_of_commas( $len ) {
		$avg    = ( float ) log( $len, 6 );
		$stdDev = ( float ) $avg / 6.000;

		return ( int ) round( $this->gauss_ms( $avg, $stdDev ) );
	}

	/**
	 * Returns a number on a gaussian distribution
	 * based on the average word length of an english
	 * sentence.
	 * Statistics Source:
	 * 	http://hearle.nahoo.net/Academic/Maths/Sentence.html
	 * 	Average: 24.46
	 * 	Standard Deviation: 5.08
	 * 
	 * @return number
	 */
	function gaussian_sentence() {
		$avg    = ( float ) 24.460;
		$stdDev = ( float ) 5.080;

		return ( int ) round( $this->gauss_ms( $avg, $stdDev ) );
	}

	/**
	 * compute numbers with a guassian distrobution
	 * Source:
	 * 	http://us.php.net/manual/en/function.rand.php#53784
	 * 
	 * @return number
	 */
	function gauss() {   // N( 0,1 )
		// returns random number with normal distribution:
		//   mean=0
		//   std dev=1
		// auxilary vars
		$x = $this->random_0_1();
		$y = $this->random_0_1();

		// two independent variables with normal distribution N( 0,1 )
		$u = sqrt( -2 * log( $x ) ) * cos( 2 * pi() * $y );
		$v = sqrt( -2 * log( $x ) ) * sin( 2 * pi() * $y );

		// i will return only one, couse only one needed
		return $u;
	}

	/**
	 * Compute numbers with a guassian distrobution
	 * 
	 * @param real $m
	 * @param real $s
	 * 
	 * @return number
	 */
	function gauss_ms( $m = 0.0, $s = 1.0 ) {
		return $this->gauss() * $s + $m;
	}

	/**
	 * Get random number
	 * 
	 * @return number
	 */
	function random_0_1() {
		return ( float ) rand() / ( float ) getrandmax();
	}

}