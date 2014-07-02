<?php return array(

/**
 * -----------------------------------------------------------------------------
 * Source Dir
 * -----------------------------------------------------------------------------
 *
 * Source directory from which markdown file will be generated. The default is
 * already defined for convenience, feel free to change it to wherever you need
 * but note that it should be existing directory otherwise it'll generate error
 */
	'source' => __DIR__ . '/source',

/**
 * -----------------------------------------------------------------------------
 * Destination Dir
 * -----------------------------------------------------------------------------
 *
 * Destination directory to which generated html will be putted into. The default
 * is already defined for convenience, feel free to change it to wherever you need.
 * If directory doesn't exist yet, it'll automaticly created during generation.
 */
	'destination' => __DIR__ . '/generated',

/**
 * -----------------------------------------------------------------------------
 * Template
 * -----------------------------------------------------------------------------
 *
 * Template is used to wrap generated html. Things that you have to define is
 * template directory and template file itself. Pharkd uses Twig as templating
 * system. Note that 'template.dir' must be always set to existing dir. If no
 * 'template' defined, then no templating.
 */

	'template' => '',
	'template.dir' => __DIR__,

);