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
    		global $USERINFO;
    		$modified_file = $event->data[0][0];
    		$debug = $keyvalue = $this->getConf('debug');
    		
    		
    		$curr_dir = getcwd();												// Save where I am now
    		$dirname = dirname($modified_file);					// The dir or the file that was changed
    		$basename = basename($modified_file);				// The filename of the file that was changed
    		chdir($dirname);														// Change to the folder where the file changed
    		
    		if ($debug) {
	    		msg("AllowDebug " . $debug);
  	  		msg("Filename " . $modified_file);
    			msg("Currdir " . getcwd());
    			msg("Basename " . $basename);
    			msg("Event <pre>" . print_r($event, TRUE) . "</pre>");
	    		msg("Param <pre>" . print_r($param, TRUE) . "</pre>");
  	  		msg("Userinfo <pre>" . print_r($USERINFO, TRUE) . "</pre>");
  	  	}

    		$commit_message = sprintf("\"User [%s]\nModified [%s]\"",
    			$USERINFO['name'], $modified_file);
    		
    		$output = array();
    		exec("/usr/bin/git add " . $basename, $output, $rc);
    		if ($debug) {
    			msg("Output1 [" . $rc . "] <pre>" . print_r($output, TRUE) . "</pre>");
    		}

    		$output = array();
    		exec("/usr/bin/git commit " . $basename . " -m " . $commit_message, $output, $rc);
    		if ($debug) {
    			msg("Output2 [" . $rc . "] <pre>" . print_r($output, TRUE) . "</pre>");
    		}    		

    		$output = array();
    		exec("/usr/bin/git pull", $output, $rc);
    		if ($debug) {
    			msg("Output4 [" . $rc . "] <pre>" . print_r($output, TRUE) . "</pre>");
    		}
    		
    		$output = array();
    		exec("/usr/bin/git push", $output, $rc);
    		if ($debug) {
    			msg("Output3 [" . $rc . "] <pre>" . print_r($output, TRUE) . "</pre>");
				}
    		
    		chdir($curr_dir);													// Change back to where we were
    	}
    	// msg("Event <pre>" . print_r($event, TRUE) . "</pre>");
    	// msg("Param <pre>" . print_r($param, TRUE) . "</pre>");
    }

}

// vim:ts=4:sw=4:et:
