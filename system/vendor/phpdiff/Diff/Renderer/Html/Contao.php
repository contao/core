<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Diff
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

require_once dirname(__FILE__).'/Array.php';

class Diff_Renderer_Html_Contao extends Diff_Renderer_Html_Array
{
	/**
	 * Render a and return diff with changes between the two sequences
	 * displayed inline (under each other)
	 *
	 * @return string The generated inline diff.
	 */
	public function render()
	{
		$changes = parent::render();

		if (empty($changes)) {
			return '';
		}

		$html = "\n".'<div class="change">';

		// Add the field name
		if (isset($this->options['field']))	{
			$html .= "\n<h2>".$this->options['field'].'</h2>';
		}

		$html .= "\n<dl>";

		foreach ($changes as $i=>$blocks) {
			// If this is a separate block, we're condensing code so output …,
			// indicating a significant portion of the code has been collapsed
			if ($i > 0) {
				$html .= '<dt class="skipped">…</dt>';
			}

			foreach ($blocks as $change) {
				// Equal changes should be shown on both sides of the diff
				if ($change['tag'] == 'equal') {
					foreach ($change['base']['lines'] as $line) {
						$html .= "\n  ".'<dt class="'.$change['tag'].' left">'.($line ?: '&nbsp').'</dt>';
					}
				}
				// Added lines only on the right side
				elseif ($change['tag'] == 'insert') {
					foreach ($change['changed']['lines'] as $line) {
						$html .= "\n ".'<dt class="'.$change['tag'].' right"><ins>'.($line ?: '&nbsp').'</ins></dt>';
					}
				}
				// Show deleted lines only on the left side
				elseif ($change['tag'] == 'delete') {
					foreach ($change['base']['lines'] as $line) {
						$html .= "\n  ".'<dt class="'.$change['tag'].' left"><del>'.($line ?: '&nbsp').'</del></dt>';
					}
				}
				// Show modified lines on both sides
				elseif ($change['tag'] == 'replace') {
					foreach ($change['base']['lines'] as $line) {
						$html .= "\n  ".'<dt class="'.$change['tag'].' left"><span>'.($line ?: '&nbsp').'</span></dt>';
					}
					foreach ($change['changed']['lines'] as $line) {
						$html .= "\n  ".'<dd class="'.$change['tag'].' right"><span>'.($line ?: '&nbsp').'</span></dd>';
					}
				}
			}
		}

		$html .= "\n</dl>\n</div>\n";
		return $html;
	}
}