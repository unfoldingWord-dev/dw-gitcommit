<?php
/**
 * DokuWiki Plugin gitcommit (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Dave Pearce <dave@distantshores.org>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class action_plugin_gitcommit_addcommit extends DokuWiki_Action_Plugin {

    /**
     * Registers a callback function for a given event
     *
     * @param Doku_Event_Handler $controller DokuWiki's event controller object
     * @return void
     */
    public function register(Doku_Event_Handler $controller) {

       $controller->register_hook('IO_WIKIPAGE_WRITE', 'AFTER', $this, 'handle_io_wikipage_write');
   
    }

    /**
     * [Custom event handler which performs action]
     *
     * @param Doku_Event $event  event object by reference
     * @param mixed      $param  [the parameters passed as fifth argument to register_hook() when this
     *                           handler was registered]
     * @return void
     */

    public function handle_io_wikipage_write(Doku_Event &$event, $param) {
    	if (empty($event->data[3])) {
    		$curr_dir = getcwd();
    		$dirname = dirname($event->data[0][0]);
    		$basename = basename($event->data[0][0]);
    		chdir($dirname);
    		
    		msg("Filename " . $event->data[0][0]);
    		msg("Currdir " . getcwd());
    		msg("Basename " . $basename);
    		
    		$output = array();
    		exec("/usr/bin/git add " . $basename, $output);
    		msg("Output1 <pre>" . print_r($output, TRUE) . "</pre>");

    		$output = array();
    		exec("/usr/bin/git commit " . $basename . " -m 'Another'", &$output);
    		msg("Output2 <pre>" . print_r($output, TRUE) . "</pre>");
    		
    		chdir($curr_dir);
    	}
    	// msg("Event <pre>" . print_r($event, TRUE) . "</pre>");
    	// msg("Param <pre>" . print_r($param, TRUE) . "</pre>");
    }

}

// vim:ts=4:sw=4:et:
