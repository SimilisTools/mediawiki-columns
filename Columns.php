<?php

if( !defined( 'MEDIAWIKI' ) ) {
	die("This file is an extension to the MediaWiki software and cannot be used standalone.\n");
}


//self executing anonymous function to prevent global scope assumptions
call_user_func( function() {

	$GLOBALS['wgExtensionCredits']['parserhook'][] = array(
			'path' => __FILE__,
			'name' => "Columns",
			'description' => "Very simple parser function for creating div columns",
			'version' => 0.2,
			'author' => "@toniher",
			'url' => "https://mediawiki.org/wiki/User:Toniher",
	);

	$GLOBALS['wgHooks']['ParserFirstCallInit'][] = 'wfColumnsSetup';

});

function wfColumnsSetup( Parser $parser ) {
	$parser->setHook( 'columns', 'wfColumnsRender' );
	return true;
}


function wfColumnsRender( $text, array $args, Parser $parser, PPFrame $frame ) {

	$output = $parser->recursiveTagParse( $text, $frame );
	$columns = 10;
	$format = "wiki";
	$classmain = "columns";
	$class = "column";
	
	if (isset($args['entries'])) {
		if(is_numeric($args['entries'])) {
			$columns = $args['entries'];
		}
	}
	
	if (isset($args['format'])) {
		$format = $args['format'];
	}
	
	if (isset($args['classmain'])) {
		$classmain = $args['classmain'];
	}
	
	if (isset($args['class'])) {
		$class = $args['class'];
	}
	
	
	if ($format == 'html') {
	
		$output = str_replace("<ul>", "", $output);
		$output = str_replace("</ul>", "", $output);
		$output = str_replace("</li>", "", $output);
		$output = str_replace("<p>", "", $output);
		$output = str_replace("</p>", "", $output);
		$listli = split("<li>", $output);
		$final = "";
		$iter = 0;
	
		for ($i=1; $i<count($listli);$i++) {
			if ($iter==0) {
				$final.="<div class='".$class."'><ul>";
			}

			$final.="<li>".$listli[$i]."</li>";

			$iter++;
			if ($iter == $columns) {
				$final.="</ul></div>";
				$iter=0;
			}
			else {
				if ($i == count($listli2)-1) {
						$final.="</ul></div>";
				}
			}
		}
	
		return '<div class="'.$classmain.'">' . $final . '</div>';

	} else {
	
		$listli = split("\*", $output);
		$final = "";
		$iter = 0;

		for ($i=1; $i<count($listli);$i++) {
	
			if ($iter==0) {
					$final.="<div class='".$class."'>\n";
			}

			$final.="* ".$listli[$i];

			$iter++;
			if ($iter == $columns) {
					$final.="</div>";
					$iter=0;
			}
			else {
					if ($i == count($listli)-1) {
							$final.="</div>";
					}
			}
		}
	
		return '<div class="'.$classmain.'">' . $final . '</div>';
	
	}

}

