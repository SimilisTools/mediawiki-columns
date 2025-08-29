<?php

namespace MediaWiki\Extension\Columns;

use Parser;
use PPFrame;

class Columns
{
    public static function onParserFirstCallInit(Parser $parser)
    {
        $parser->setHook('columns', [ self::class, 'render' ]);
        return true;
    }

    public static function render($text, array $args, Parser $parser, PPFrame $frame)
    {
        $output = $parser->recursiveTagParse($text, $frame);
        $columns = 10;
        $format = "wiki";
        $classmain = "columns";
        $class = "column";

        if (isset($args["entries"]) && is_numeric($args["entries"])) {
            $columns = $args["entries"];
        }
        if (isset($args["format"])) {
            $format = $args["format"];
        }
        if (isset($args["classmain"])) {
            $classmain = $args["classmain"];
        }
        if (isset($args["class"])) {
            $class = $args["class"];
        }

        if ($format == "html") {
            $output = str_replace([ "<ul>", "</ul>", "</li>", "<p>", "</p>" ], "", $output);
            $listli = explode("<li>", $output);
            $final = "";
            $iter = 0;

            for ($i = 1; $i < count($listli); $i++) {
                if ($iter == 0) {
                    $final .= "<div class='" . $class . "'><ul>";
                }
                $final .= "<li>" . $listli[$i] . "</li>";
                $iter++;
                if ($iter == $columns) {
                    $final .= "</ul></div>";
                    $iter = 0;
                } elseif ($i == count($listli) - 1) {
                    $final .= "</ul></div>";
                }
            }
            return '<div class="' . $classmain . '">' . $final . "</div>";
        } else {
            $listli = explode("\*", $output);
            $final = "";
            $iter = 0;

            for ($i = 1; $i < count($listli); $i++) {
                if ($iter == 0) {
                    $final .= "<div class='" . $class . "'>\n";
                }
                $final .= "* " . $listli[$i];
                $iter++;
                if ($iter == $columns) {
                    $final .= "</div>";
                    $iter = 0;
                } elseif ($i == count($listli) - 1) {
                    $final .= "</div>";
                }
            }
            return '<div class="' . $classmain . '">' . $final . "</div>";
        }
    }
}
