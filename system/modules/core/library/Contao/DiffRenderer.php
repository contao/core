<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Renders an inline diff view using definition list markup
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class DiffRenderer extends \Diff_Renderer_Html_Array
{

	/**
	 * Render the diff and return the generated markup
	 *
	 * @return string The generated markup
	 */
	public function render()
	{
		$changes = parent::render();

		if (empty($changes))
		{
			return '';
		}

		$html = "\n" . '<div class="change">';

		// Add the field name
		if (isset($this->options['field']))
		{
			$html .= "\n<h2>" . $this->options['field'] . '</h2>';
		}

		$html .= "\n<dl>";

		foreach ($changes as $i=>$blocks)
		{
			if ($i > 0)
			{
				$html .= '<dt class="skipped">…</dt>';
			}

			foreach ($blocks as $change)
			{
				// Show equal changes on both sides of the diff
				if ($change['tag'] == 'equal')
				{
					foreach ($change['base']['lines'] as $line)
					{
						$html .= "\n  " . '<dt class="' . $change['tag'] . ' left">' . ($line ?: '&nbsp;') . '</dt>';
					}

				}

				// Show added lines only on the right side
				elseif ($change['tag'] == 'insert')
				{
					foreach ($change['changed']['lines'] as $line)
					{
						$html .= "\n " . '<dt class="' . $change['tag'] . ' right"><ins>' . ($line ?: '&nbsp;') . '</ins></dt>';
					}
				}

				// Show deleted lines only on the left side
				elseif ($change['tag'] == 'delete')
				{
					foreach ($change['base']['lines'] as $line)
					{
						$html .= "\n  " . '<dt class="' . $change['tag'] . ' left"><del>' . ($line ?: '&nbsp;') . '</del></dt>';
					}
				}

				// Show modified lines on both sides
				elseif ($change['tag'] == 'replace')
				{
					foreach ($change['base']['lines'] as $line)
					{
						$html .= "\n  " . '<dt class="' . $change['tag'] . ' left"><span>' . ($line ?: '&nbsp;') . '</span></dt>';
					}

					foreach ($change['changed']['lines'] as $line)
					{
						$html .= "\n  " . '<dd class="' . $change['tag'] . ' right"><span>' . ($line ?: '&nbsp;') . '</span></dd>';
					}
				}
			}
		}

		$html .= "\n</dl>\n</div>\n";

		return $html;
	}
}
