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
 * Provide methods to handle style sheets.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class StyleSheets extends \Backend
{

	/**
	 * Import the Files library
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('Files');
	}


	/**
	 * Update a particular style sheet
	 *
	 * @param integer $intId
	 */
	public function updateStyleSheet($intId)
	{
		$objStyleSheet = $this->Database->prepare("SELECT * FROM tl_style_sheet WHERE id=?")
										->limit(1)
										->execute($intId);

		if ($objStyleSheet->numRows < 1)
		{
			return;
		}

		// Delete the CSS file
		if (\Input::get('act') == 'delete')
		{
			$this->import('Files');
			$this->Files->delete('assets/css/' . $objStyleSheet->name . '.css');
		}

		// Update the CSS file
		else
		{
			$this->writeStyleSheet($objStyleSheet->row());
			$this->log('Generated style sheet "' . $objStyleSheet->name . '.css"', __METHOD__, TL_CRON);
		}
	}


	/**
	 * Update all style sheets in the scripts folder
	 */
	public function updateStyleSheets()
	{
		$objStyleSheets = $this->Database->execute("SELECT * FROM tl_style_sheet");
		$arrStyleSheets = $objStyleSheets->fetchEach('name');

		// Make sure the dcaconfig.php file is loaded
		@include TL_ROOT . '/system/config/dcaconfig.php';

		// Delete old style sheets
		foreach (scan(TL_ROOT . '/assets/css', true) as $file)
		{
			// Skip directories
			if (is_dir(TL_ROOT . '/assets/css/' . $file))
			{
				continue;
			}

			// Preserve root files (is this still required now that scripts are in assets/css/scripts?)
			if (is_array(\Config::get('rootFiles')) && in_array($file, \Config::get('rootFiles')))
			{
				continue;
			}

			// Do not delete the combined files (see #3605)
			if (preg_match('/^[a-f0-9]{12}\.css$/', $file))
			{
				continue;
			}

			$objFile = new \File('assets/css/' . $file, true);

			// Delete the old style sheet
			if ($objFile->extension == 'css' && !in_array($objFile->filename, $arrStyleSheets))
			{
				$objFile->delete();
			}
		}

		$objStyleSheets->reset();

		// Create the new style sheets
		while ($objStyleSheets->next())
		{
			$this->writeStyleSheet($objStyleSheets->row());
			$this->log('Generated style sheet "' . $objStyleSheets->name . '.css"', __METHOD__, TL_CRON);
		}
	}


	/**
	 * Write a style sheet to a file
	 *
	 * @param array $row
	 */
	protected function writeStyleSheet($row)
	{
		if ($row['id'] == '' || $row['name'] == '')
		{
			return;
		}

		$row['name'] = basename($row['name']);

		// Check whether the target file is writeable
		if (file_exists(TL_ROOT . '/assets/css/' . $row['name'] . '.css') && !$this->Files->is_writeable('assets/css/' . $row['name'] . '.css'))
		{
			\Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['notWriteable'], 'assets/css/' . $row['name'] . '.css'));

			return;
		}

		$vars = array();

		// Get the global theme variables
		$objTheme = $this->Database->prepare("SELECT vars FROM tl_theme WHERE id=?")
								   ->limit(1)
								   ->execute($row['pid']);

		if ($objTheme->vars != '')
		{
			if (is_array(($tmp = deserialize($objTheme->vars))))
			{
				foreach ($tmp as $v)
				{
					$vars[$v['key']] = $v['value'];
				}
			}
		}

		// Merge the global style sheet variables
		if ($row['vars'] != '')
		{
			if (is_array(($tmp = deserialize($row['vars']))))
			{
				foreach ($tmp as $v)
				{
					$vars[$v['key']] = $v['value'];
				}
			}
		}

		// Sort by key length (see #3316)
		uksort($vars, 'length_sort_desc');

		// Create the file
		$objFile = new \File('assets/css/' . $row['name'] . '.css', true);
		$objFile->write('/* ' . $row['name'] . ".css */\n");

		$objDefinitions = $this->Database->prepare("SELECT * FROM tl_style WHERE pid=? AND invisible!='1' ORDER BY sorting")
										 ->execute($row['id']);

		// Append the definition
		while ($objDefinitions->next())
		{
			$objFile->append($this->compileDefinition($objDefinitions->row(), true, $vars, $row), '');
		}

		$objFile->close();
	}


	/**
	 * Compile format definitions and return them as string
	 *
	 * @param array   $row
	 * @param boolean $blnWriteToFile
	 * @param array   $vars
	 * @param array   $parent
	 * @param boolean $export
	 *
	 * @return string
	 */
	public function compileDefinition($row, $blnWriteToFile=false, $vars=array(), $parent=array(), $export=false)
	{
		if ($blnWriteToFile)
		{
			$strGlue = '../../';
			$lb = '';
			$return = '';
		}
		elseif ($export)
		{
			$strGlue = '';
			$lb = "\n    ";
			$return = '';
		}
		else
		{
			$strGlue = '';
			$lb = "\n    ";
			$return = "\n" . '<pre'. ($row['invisible'] ? ' class="disabled"' : '') .'>';
		}

		$blnNeedsPie = false;

		// Comment
		if ((!$blnWriteToFile || $export) && $row['comment'] != '')
		{
			$search = array('@^\s*/\*+@', '@\*+/\s*$@');
			$comment = preg_replace($search, '', $row['comment']);

			if ($export)
			{
				$return .= "\n/* " . $comment . " */\n";
			}
			else
			{
				$comment = wordwrap(trim($comment), 72);
				$return .= "\n" . '<span class="comment">' . $comment . '</span>' . "\n";
			}
		}

		// Selector
		$arrSelector = trimsplit(',', \String::decodeEntities($row['selector']));
		$return .= implode(($blnWriteToFile ? ',' : ",\n"), $arrSelector) . ($blnWriteToFile ? '' : ' ') . '{';

		// Size
		if ($row['size'])
		{
			// Width
			$row['width'] = deserialize($row['width']);

			if (isset($row['width']['value']) && $row['width']['value'] != '')
			{
				$return .= $lb . 'width:' . $row['width']['value'] . (($row['width']['value'] == 'auto') ? '' : $row['width']['unit']) . ';';
			}

			// Height
			$row['height'] = deserialize($row['height']);

			if (isset($row['height']['value']) && $row['height']['value'] != '')
			{
				$return .= $lb . 'height:' . $row['height']['value'] . (($row['height']['value'] == 'auto') ? '' : $row['height']['unit']) . ';';
			}

			// Min-width
			$row['minwidth'] = deserialize($row['minwidth']);

			if (isset($row['minwidth']['value']) && $row['minwidth']['value'] != '')
			{
				$return .= $lb . 'min-width:' . $row['minwidth']['value'] . (($row['minwidth']['value'] == 'inherit') ? '' : $row['minwidth']['unit']) . ';';
			}

			// Min-height
			$row['minheight'] = deserialize($row['minheight']);

			if (isset($row['minheight']['value']) && $row['minheight']['value'] != '')
			{
				$return .= $lb . 'min-height:' . $row['minheight']['value'] . (($row['minheight']['value'] == 'inherit') ? '' : $row['minheight']['unit']) . ';';
			}

			// Max-width
			$row['maxwidth'] = deserialize($row['maxwidth']);

			if (isset($row['maxwidth']['value']) && $row['maxwidth']['value'] != '')
			{
				$return .= $lb . 'max-width:' . $row['maxwidth']['value'] . (($row['maxwidth']['value'] == 'inherit' || $row['maxwidth']['value'] == 'none') ? '' : $row['maxwidth']['unit']) . ';';
			}

			// Max-height
			$row['maxheight'] = deserialize($row['maxheight']);

			if (isset($row['maxheight']['value']) && $row['maxheight']['value'] != '')
			{
				$return .= $lb . 'max-height:' . $row['maxheight']['value'] . (($row['maxheight']['value'] == 'inherit' || $row['maxheight']['value'] == 'none') ? '' : $row['maxheight']['unit']) . ';';
			}
		}

		// Position
		if ($row['positioning'])
		{
			// Top/right/bottom/left
			$row['trbl'] = deserialize($row['trbl']);

			if (is_array($row['trbl']))
			{
				foreach ($row['trbl'] as $k=>$v)
				{
					if ($v != '' && $k != 'unit')
					{
						$return .= $lb . $k . ':' . $v . (($v == 'auto' || $v === '0') ? '' : $row['trbl']['unit']) . ';';
					}
				}
			}

			// Position
			if ($row['position'] != '')
			{
				$return .= $lb . 'position:' . $row['position'] . ';';
			}

			// Overflow
			if ($row['overflow'] != '')
			{
				$return .= $lb . 'overflow:' . $row['overflow'] . ';';
			}

			// Float
			if ($row['floating'] != '')
			{
				$return .= $lb . 'float:' . $row['floating'] . ';';
			}

			// Clear
			if ($row['clear'] != '')
			{
				$return .= $lb . 'clear:' . $row['clear'] . ';';
			}

			// Display
			if ($row['display'] != '')
			{
				$return .= $lb . 'display:' . $row['display'] . ';';
			}
		}

		// Margin, padding and alignment
		if ($row['alignment'])
		{
			// Margin
			if ($row['margin'] != '' || $row['align'] != '')
			{
				$row['margin'] = deserialize($row['margin']);

				if (is_array($row['margin']))
				{
					$top = $row['margin']['top'];
					$right = $row['margin']['right'];
					$bottom = $row['margin']['bottom'];
					$left = $row['margin']['left'];

					// Overwrite the left and right margin if an alignment is set
					if ($row['align'] != '')
					{
						if ($row['align'] == 'left' || $row['align'] == 'center')
						{
							$right = 'auto';
						}

						if ($row['align'] == 'right' || $row['align'] == 'center')
						{
							$left = 'auto';
						}
					}

					// Try to shorten the definition
					if ($top != '' && $right != '' && $bottom != '' && $left != '')
					{
						if ($top == $right && $top == $bottom && $top == $left)
						{
							$return .= $lb . 'margin:' . $top . (($top == 'auto' || $top === '0') ? '' : $row['margin']['unit']) . ';';
						}
						elseif ($top == $bottom && $right == $left)
						{
							$return .= $lb . 'margin:' . $top . (($top == 'auto' || $top === '0') ? '' : $row['margin']['unit']) . ' ' . $right . (($right == 'auto' || $right === '0') ? '' : $row['margin']['unit']) . ';';
						}
						elseif ($top != $bottom && $right == $left)
						{
							$return .= $lb . 'margin:' . $top . (($top == 'auto' || $top === '0') ? '' : $row['margin']['unit']) . ' ' . $right . (($right == 'auto' || $right === '0') ? '' : $row['margin']['unit']) . ' ' . $bottom . (($bottom == 'auto' || $bottom === '0') ? '' : $row['margin']['unit']) . ';';
						}
						else
						{
							$return .= $lb . 'margin:' . $top . (($top == 'auto' || $top === '0') ? '' : $row['margin']['unit']) . ' ' . $right . (($right == 'auto' || $right === '0') ? '' : $row['margin']['unit']) . ' ' . $bottom . (($bottom == 'auto' || $bottom === '0') ? '' : $row['margin']['unit']) . ' ' . $left . (($left == 'auto' || $left === '0') ? '' : $row['margin']['unit']) . ';';
						}
					}
					else
					{
						$arrDir = array('top'=>$top, 'right'=>$right, 'bottom'=>$bottom, 'left'=>$left);

						foreach ($arrDir as $k=>$v)
						{
							if ($v != '')
							{
								$return .= $lb . 'margin-' . $k . ':' . $v . (($v == 'auto' || $v === '0') ? '' : $row['margin']['unit']) . ';';
							}
						}
					}
				}
			}

			// Padding
			if ($row['padding'] != '')
			{
				$row['padding'] = deserialize($row['padding']);

				if (is_array($row['padding']))
				{
					$top = $row['padding']['top'];
					$right = $row['padding']['right'];
					$bottom = $row['padding']['bottom'];
					$left = $row['padding']['left'];

					// Try to shorten the definition
					if ($top != '' && $right != '' && $bottom != '' && $left != '')
					{
						if ($top == $right && $top == $bottom && $top == $left)
						{
							$return .= $lb . 'padding:' . $top . (($top === '0') ? '' : $row['padding']['unit']) . ';';
						}
						elseif ($top == $bottom && $right == $left)
						{
							$return .= $lb . 'padding:' . $top . (($top === '0') ? '' : $row['padding']['unit']) . ' ' . $right . (($right === '0') ? '' : $row['padding']['unit']) . ';';
						}
						elseif ($top != $bottom && $right == $left)
						{
							$return .= $lb . 'padding:' . $top . (($top === '0') ? '' : $row['padding']['unit']) . ' ' . $right . (($right === '0') ? '' : $row['padding']['unit']) . ' ' . $bottom . (($bottom === '0') ? '' : $row['padding']['unit']) . ';';
						}
						else
						{
							$return .= $lb . 'padding:' . $top . (($top === '0') ? '' : $row['padding']['unit']) . ' ' . $right . (($right === '0') ? '' : $row['padding']['unit']) . ' ' . $bottom . (($bottom === '0') ? '' : $row['padding']['unit']) . ' ' . $left . (($left === '0') ? '' : $row['padding']['unit']) . ';';
						}
					}
					else
					{
						$arrDir = array('top'=>$top, 'right'=>$right, 'bottom'=>$bottom, 'left'=>$left);

						foreach ($arrDir as $k=>$v)
						{
							if ($v != '')
							{
								$return .= $lb . 'padding-' . $k . ':' . $v . (($v === '0') ? '' : $row['padding']['unit']) . ';';
							}
						}
					}
				}
			}

			// Vertical alignment
			if ($row['verticalalign'] != '')
			{
				$return .= $lb . 'vertical-align:' . $row['verticalalign'] . ';';
			}

			// Text alignment
			if ($row['textalign'] != '')
			{
				$return .= $lb . 'text-align:' . $row['textalign'] . ';';
			}

			// White space
			if ($row['whitespace'] != '')
			{
				$return .= $lb . 'white-space:' . $row['whitespace'] . ';';
			}
		}

		// Background
		if ($row['background'])
		{
			$bgColor = deserialize($row['bgcolor'], true);

			// Try to shorten the definition
			if ($bgColor[0] != '' && $row['bgimage'] != '' && $row['bgposition'] != '' && $row['bgrepeat'] != '')
			{
				if (($strImage = $this->generateBase64Image($row['bgimage'], $parent)) !== false)
				{
					$return .= $lb . 'background:' . $this->compileColor($bgColor, $blnWriteToFile, $vars) . ' url("' . $strImage . '") ' . $row['bgposition'] . ' ' . $row['bgrepeat'] . ';';
				}
				else
				{
					$glue = (strncmp($row['bgimage'], 'data:', 5) !== 0 && strncmp($row['bgimage'], 'http://', 7) !== 0 && strncmp($row['bgimage'], 'https://', 8) !== 0 && strncmp($row['bgimage'], '/', 1) !== 0) ? $strGlue : '';
					$return .= $lb . 'background:' . $this->compileColor($bgColor, $blnWriteToFile, $vars) . ' url("' . $glue . $row['bgimage'] . '") ' . $row['bgposition'] . ' ' . $row['bgrepeat'] . ';';
				}
			}
			else
			{
				// Background color
				if ($bgColor[0] != '')
				{
					$return .= $lb . 'background-color:' . $this->compileColor($bgColor, $blnWriteToFile, $vars) . ';';
				}

				// Background image
				if ($row['bgimage'] == 'none')
				{
					$return .= $lb . 'background-image:none;';
				}
				elseif ($row['bgimage'] != '')
				{
					if (($strImage = $this->generateBase64Image($row['bgimage'], $parent)) !== false)
					{
						$return .= $lb . 'background-image:url("' . $strImage . '");';
					}
					else
					{
						$glue = (strncmp($row['bgimage'], 'data:', 5) !== 0 && strncmp($row['bgimage'], 'http://', 7) !== 0 && strncmp($row['bgimage'], 'https://', 8) !== 0 && strncmp($row['bgimage'], '/', 1) !== 0) ? $strGlue : '';
						$return .= $lb . 'background-image:url("' . $glue . $row['bgimage'] . '");';
					}
				}

				// Background position
				if ($row['bgposition'] != '')
				{
					$return .= $lb . 'background-position:' .$row['bgposition']. ';';
				}

				// Background repeat
				if ($row['bgrepeat'] != '')
				{
					$return .= $lb . 'background-repeat:' .$row['bgrepeat']. ';';
				}
			}

			// Background gradient
			if ($row['gradientAngle'] != '' && $row['gradientColors'] != '')
			{
				$row['gradientColors'] = deserialize($row['gradientColors']);

				if (is_array($row['gradientColors']) && count(array_filter($row['gradientColors'])) > 0)
				{
					$blnNeedsPie = true;
					$bgImage = '';

					// CSS3 PIE only supports -pie-background, so if there is a background image, include it here, too.
					if ($row['bgimage'] != '' && $row['bgposition'] != '' && $row['bgrepeat'] != '')
					{
						$glue = (strncmp($row['bgimage'], 'data:', 5) !== 0 && strncmp($row['bgimage'], 'http://', 7) !== 0 && strncmp($row['bgimage'], 'https://', 8) !== 0 && strncmp($row['bgimage'], '/', 1) !== 0) ? $strGlue : '';
						$bgImage = 'url("' . $glue . $row['bgimage'] . '") ' . $row['bgposition'] . ' ' . $row['bgrepeat'] . ',';
					}

					// Default starting point
					if ($row['gradientAngle'] == '')
					{
						$row['gradientAngle'] = 'to top';
					}

					$row['gradientColors'] = array_values(array_filter($row['gradientColors']));

					// Add a hash tag to the color values
					foreach ($row['gradientColors'] as $k=>$v)
					{
						$row['gradientColors'][$k] = '#' . $v;
					}

					$angle = '';

					// Convert the angle for the legacy commands (see #4569)
					if (strpos($row['gradientAngle'], 'deg') !== false)
					{
						$angle = (abs(intval($row['gradientAngle']) - 450) % 360) . 'deg';
					}
					else
					{
						switch ($row['gradientAngle'])
						{
							case 'to top':          $angle = 'bottom';       break;
							case 'to right':        $angle = 'left';         break;
							case 'to bottom':       $angle = 'top';          break;
							case 'to left':         $angle = 'right';        break;
							case 'to top left':     $angle = 'bottom right'; break;
							case 'to top right':    $angle = 'bottom left';  break;
							case 'to bottom left':  $angle = 'top right';    break;
							case 'to bottom right': $angle = 'top left';     break;
						}
					}

					$colors = implode(',', $row['gradientColors']);

					$legacy = $angle . ',' . $colors;
					$gradient = $row['gradientAngle'] . ',' . $colors;

					$return .= $lb . 'background:' . $bgImage . '-moz-linear-gradient(' . $legacy . ');';
					$return .= $lb . 'background:' . $bgImage . '-webkit-linear-gradient(' . $legacy . ');';
					$return .= $lb . 'background:' . $bgImage . '-o-linear-gradient(' . $legacy . ');';
					$return .= $lb . 'background:' . $bgImage . '-ms-linear-gradient(' . $legacy . ');';
					$return .= $lb . 'background:' . $bgImage . 'linear-gradient(' . $gradient . ');';
					$return .= $lb . '-pie-background:' . $bgImage . 'linear-gradient(' . $legacy . ');';
				}
			}

			// Box shadow
			if ($row['shadowsize'] != '')
			{
				$shColor = deserialize($row['shadowcolor'], true);
				$row['shadowsize'] = deserialize($row['shadowsize']);

				if (is_array($row['shadowsize']) && $row['shadowsize']['top'] != '' && $row['shadowsize']['right'] != '')
				{
					$blnNeedsPie = true;

					$offsetx = $row['shadowsize']['top'];
					$offsety = $row['shadowsize']['right'];
					$blursize = $row['shadowsize']['bottom'];
					$radius = $row['shadowsize']['left'];

					$shadow = $offsetx . (($offsetx === '0') ? '' : $row['shadowsize']['unit']);
					$shadow .= ' ' . $offsety . (($offsety === '0') ? '' : $row['shadowsize']['unit']);
					if ($blursize != '')
					{
						$shadow .= ' ' . $blursize . (($blursize === '0') ? '' : $row['shadowsize']['unit']);
					}
					if ($radius != '')
					{
						$shadow .= ' ' . $radius . (($radius === '0') ? '' : $row['shadowsize']['unit']);
					}
					if ($shColor[0] != '')
					{
						$shadow .= ' ' . $this->compileColor($shColor, $blnWriteToFile, $vars);
					}
					$shadow .= ';';

					// Prefix required in Safari <= 5 and Android
					$return .= $lb . '-webkit-box-shadow:' . $shadow;
					$return .= $lb . 'box-shadow:' . $shadow;
				}
			}
		}

		// Border
		if ($row['border'])
		{
			$bdColor = deserialize($row['bordercolor'], true);
			$row['borderwidth'] = deserialize($row['borderwidth']);

			// Border width
			if (is_array($row['borderwidth']))
			{
				$top = $row['borderwidth']['top'];
				$right = $row['borderwidth']['right'];
				$bottom = $row['borderwidth']['bottom'];
				$left = $row['borderwidth']['left'];

				// Try to shorten the definition
				if ($top != '' && $right != '' && $bottom != '' && $left != '' && $top == $right && $top == $bottom && $top == $left)
				{
					$return .= $lb . 'border:' . $top . $row['borderwidth']['unit'] . (($row['borderstyle'] != '') ? ' ' .$row['borderstyle'] : '') . (($bdColor[0] != '') ? ' ' . $this->compileColor($bdColor, $blnWriteToFile, $vars) : '') . ';';
				}
				elseif ($top != '' && $right != '' && $bottom != '' && $left != '' && $top == $bottom && $left == $right)
				{
					$return .= $lb . 'border-width:' . $top . $row['borderwidth']['unit'] . ' ' . $right . $row['borderwidth']['unit'] . ';';

					if ($row['borderstyle'] != '')
					{
						$return .= $lb . 'border-style:' . $row['borderstyle'] . ';';
					}

					if ($bdColor[0] != '')
					{
						$return .= $lb . 'border-color:' . $this->compileColor($bdColor, $blnWriteToFile, $vars) . ';';
					}
				}
				elseif ($top == '' && $right == '' && $bottom == '' && $left == '')
				{
					if ($row['borderstyle'] != '')
					{
						$return .= $lb . 'border-style:' . $row['borderstyle'] . ';';
					}

					if ($bdColor[0] != '')
					{
						$return .= $lb . 'border-color:' . $this->compileColor($bdColor, $blnWriteToFile, $vars) . ';';
					}
				}
				else
				{
					$arrDir = array('top'=>$top, 'right'=>$right, 'bottom'=>$bottom, 'left'=>$left);

					foreach ($arrDir as $k=>$v)
					{
						if ($v != '')
						{
							$return .= $lb . 'border-' . $k . ':' . $v . $row['borderwidth']['unit'] . (($row['borderstyle'] != '') ? ' ' . $row['borderstyle'] : '') . (($bdColor[0] != '') ? ' ' . $this->compileColor($bdColor, $blnWriteToFile, $vars) : '') . ';';
						}
					}
				}
			}
			else
			{
				if ($row['borderstyle'] != '')
				{
					$return .= $lb . 'border-style:' . $row['borderstyle'] . ';';
				}

				if ($bdColor[0] != '')
				{
					$return .= $lb . 'border-color:' . $this->compileColor($bdColor, $blnWriteToFile, $vars) . ';';
				}
			}

			// Border radius
			if ($row['borderradius'] != '')
			{
				$row['borderradius'] = deserialize($row['borderradius']);

				if (is_array($row['borderradius']) && ($row['borderradius']['top'] != '' || $row['borderradius']['right'] != '' || $row['borderradius']['bottom'] != '' || $row['borderradius']['left'] != ''))
				{
					$blnNeedsPie = true;

					$top = $row['borderradius']['top'];
					$right = $row['borderradius']['right'];
					$bottom = $row['borderradius']['bottom'];
					$left = $row['borderradius']['left'];
					$borderradius = '';

					// Try to shorten the definition
					if ($top != '' && $right != '' && $bottom != '' && $left != '')
					{
						if ($top == $right && $top == $bottom && $top == $left)
						{
							$borderradius = $top . (($top === '0') ? '' : $row['borderradius']['unit']) . ';';
						}
						elseif ($top == $bottom && $right == $left)
						{
							$borderradius = $top . (($top === '0') ? '' : $row['borderradius']['unit']) . ' ' . $right . (($right === '0') ? '' : $row['borderradius']['unit']) . ';';
						}
						elseif ($top != $bottom && $right == $left)
						{
							$borderradius = $top . (($top === '0') ? '' : $row['borderradius']['unit']) . ' ' . $right . (($right === '0') ? '' : $row['borderradius']['unit']) . ' ' . $bottom . (($bottom === '0') ? '' : $row['borderradius']['unit']) . ';';
						}
						else
						{
							$borderradius .= $top . (($top === '0') ? '' : $row['borderradius']['unit']) . ' ' . $right . (($right === '0') ? '' : $row['borderradius']['unit']) . ' ' . $bottom . (($bottom === '0') ? '' : $row['borderradius']['unit']) . ' ' . $left . (($left === '0') ? '' : $row['borderradius']['unit']) . ';';
						}

						$return .= $lb . 'border-radius:' . $borderradius;
					}
					else
					{
						$arrDir = array('top-left'=>$top, 'top-right'=>$right, 'bottom-right'=>$bottom, 'bottom-left'=>$left);

						foreach ($arrDir as $k=>$v)
						{
							if ($v != '')
							{
								$return .= $lb . 'border-' . $k . '-radius:' . $v . (($v === '0') ? '' : $row['borderradius']['unit']) . ';';
							}
						}
					}
				}
			}

			// Border collapse
			if ($row['bordercollapse'] != '')
			{
				$return .= $lb . 'border-collapse:' . $row['bordercollapse'] . ';';
			}

			// Border spacing
			$row['borderspacing'] = deserialize($row['borderspacing']);

			if (isset($row['borderspacing']['value']) && $row['borderspacing']['value'] != '')
			{
				$return .= $lb . 'border-spacing:' . $row['borderspacing']['value'] . $row['borderspacing']['unit'] . ';';
			}
		}

		// Font
		if ($row['font'])
		{
			$row['fontsize'] = deserialize($row['fontsize']);
			$row['lineheight'] = deserialize($row['lineheight']);
			$row['fontfamily'] = str_replace(', ', ',', $row['fontfamily']);

			// Try to shorten the definition
			if ($row['fontfamily'] != '' && $row['fontfamily'] != 'inherit' && isset($row['fontsize']['value']) && $row['fontsize']['value'] != '' && $row['fontsize']['value'] != 'inherit')
			{
				$return .= $lb . 'font:' . $row['fontsize']['value'] . $row['fontsize']['unit'] . ((isset($row['lineheight']['value']) && $row['lineheight']['value'] != '') ? '/' . $row['lineheight']['value'] . $row['lineheight']['unit'] : '') . ' ' . $row['fontfamily'] . ';';
			}
			else
			{
				// Font family
				if ($row['fontfamily'] != '')
				{
					$return .= $lb . 'font-family:' . $row['fontfamily'] . ';';
				}

				// Font size
				if (isset($row['fontsize']['value']) && $row['fontsize']['value'] != '')
				{
					$return .= $lb . 'font-size:' . $row['fontsize']['value'] . $row['fontsize']['unit'] . ';';
				}

				// Line height
				if (isset($row['lineheight']['value']) && $row['lineheight']['value'] != '')
				{
					$return .= $lb . 'line-height:' . $row['lineheight']['value'] . $row['lineheight']['unit'] . ';';
				}
			}

			// Font style
			$row['fontstyle'] = deserialize($row['fontstyle']);

			if (is_array($row['fontstyle']))
			{
				if (in_array('bold', $row['fontstyle']))
				{
					$return .= $lb . 'font-weight:bold;';
				}

				if (in_array('italic', $row['fontstyle']))
				{
					$return .= $lb . 'font-style:italic;';
				}

				if (in_array('normal', $row['fontstyle']))
				{
					$return .= $lb . 'font-weight:normal;';
				}

				if (in_array('underline', $row['fontstyle']))
				{
					$return .= $lb . 'text-decoration:underline;';
				}

				if (in_array('line-through', $row['fontstyle']))
				{
					$return .= $lb . 'text-decoration:line-through;';
				}

				if (in_array('overline', $row['fontstyle']))
				{
					$return .= $lb. 'text-decoration:overline;';
				}

				if (in_array('notUnderlined', $row['fontstyle']))
				{
					$return .= $lb . 'text-decoration:none;';
				}

				if (in_array('small-caps', $row['fontstyle']))
				{
					$return .= $lb . 'font-variant:small-caps;';
				}
			}

			$fnColor = deserialize($row['fontcolor'], true);

			// Font color
			if ($fnColor[0] != '')
			{
				$return .= $lb . 'color:' . $this->compileColor($fnColor, $blnWriteToFile, $vars) . ';';
			}

			// Text transform
			if ($row['texttransform'] != '')
			{
				$return .= $lb . 'text-transform:' . $row['texttransform'] . ';';
			}

			// Text indent
			$row['textindent'] = deserialize($row['textindent']);

			if (isset($row['textindent']['value']) && $row['textindent']['value'] != '')
			{
				$return .= $lb . 'text-indent:' . $row['textindent']['value'] . $row['textindent']['unit'] . ';';
			}

			// Letter spacing
			$row['letterspacing'] = deserialize($row['letterspacing']);

			if (isset($row['letterspacing']['value']) && $row['letterspacing']['value'] != '')
			{
				$return .= $lb . 'letter-spacing:' . $row['letterspacing']['value'] . $row['letterspacing']['unit'] . ';';
			}

			// Word spacing
			$row['wordspacing'] = deserialize($row['wordspacing']);

			if (isset($row['wordspacing']['value']) && $row['wordspacing']['value'] != '')
			{
				$return .= $lb . 'word-spacing:' . $row['wordspacing']['value'] . $row['wordspacing']['unit'] . ';';
			}
		}

		// List
		if ($row['list'])
		{
			// List bullet
			if ($row['liststyletype'] != '')
			{
				$return .= $lb . 'list-style-type:' . $row['liststyletype'] . ';';
			}

			// List image
			if ($row['liststyleimage'] == 'none')
			{
				$return .= $lb . 'list-style-image:none;';
			}
			elseif ($row['liststyleimage'] != '')
			{
				if (($strImage = $this->generateBase64Image($row['liststyleimage'], $parent)) !== false)
				{
					$return .= $lb . 'list-style-image:url("' . $strImage . '");';
				}
				else
				{
					$glue = (strncmp($row['liststyleimage'], 'data:', 5) !== 0 && strncmp($row['liststyleimage'], 'http://', 7) !== 0 && strncmp($row['liststyleimage'], 'https://', 8) !== 0 && strncmp($row['liststyleimage'], '/', 1) !== 0) ? $strGlue : '';
					$return .= $lb . 'list-style-image:url("' . $glue . $row['liststyleimage'] . '");';
				}
			}
		}

		// Optimize floating-point numbers (see #6634)
		$return = preg_replace('/([^0-9\.\+\-])0\.([0-9]+)/', '$1.$2', $return);

		// CSS3PIE
		if ($blnNeedsPie && !$parent['disablePie'])
		{
			$return .= $lb . 'behavior:url(\'assets/css3pie/' . $GLOBALS['TL_ASSETS']['CSS3PIE'] . '/PIE.htc\');';
		}

		// Custom code
		if ($row['own'] != '')
		{
			$own = trim(\String::decodeEntities($row['own']));
			$own = preg_replace('/url\("(?!data:|\/)/', 'url("' . $strGlue, $own);
			$own = preg_split('/[\n\r]+/', $own);
			$own = implode(($blnWriteToFile ? '' : $lb), $own);
			$return .= $lb . (!$blnWriteToFile ? specialchars($own) : $own);
		}

		// Allow custom definitions
		if (isset($GLOBALS['TL_HOOKS']['compileDefinition']) && is_array($GLOBALS['TL_HOOKS']['compileDefinition']))
		{
			foreach ($GLOBALS['TL_HOOKS']['compileDefinition'] as $callback)
			{
				$this->import($callback[0]);
				$strTemp = $this->$callback[0]->$callback[1]($row, $blnWriteToFile, $vars, $parent);

				if ($strTemp != '')
				{
					$return .= $lb . $strTemp;
				}
			}
		}

		// Close the format definition
		if ($blnWriteToFile)
		{
			// Remove the last semi-colon (;) before the closing bracket
			if (substr($return, -1) == ';')
			{
				$return = substr($return, 0, -1);
			}

			$return .= '}';
		}
		elseif ($export)
		{
			$return .= "\n}\n";
		}
		else
		{
			$return .= "\n}</pre>\n";
		}

		// Replace global variables
		if (strpos($return, '$') !== false && !empty($vars))
		{
			$return = str_replace(array_keys($vars), array_values($vars), $return);
		}

		// Replace insert tags (see #5512)
		return $this->replaceInsertTags($return, false);
	}


	/**
	 * Compile a color value and return a hex or rgba color
	 *
	 * @param mixed   $color
	 * @param boolean $blnWriteToFile
	 * @param array   $vars
	 *
	 * @return string
	 */
	protected function compileColor($color, $blnWriteToFile=false, $vars=array())
	{
		if (!is_array($color))
		{
			return '#' . $this->shortenHexColor($color);
		}
		elseif (!isset($color[1]) || empty($color[1]))
		{
			return '#' . $this->shortenHexColor($color[0]);
		}
		else
		{
			return 'rgba(' . implode(',', $this->convertHexColor($color[0], $blnWriteToFile, $vars)) . ','. ($color[1] / 100) .')';
		}
	}


	/**
	 * Try to shorten a hex color
	 *
	 * @param string $color
	 *
	 * @return string
	 */
	protected function shortenHexColor($color)
	{
		if ($color[0] == $color[1] && $color[2] == $color[3] && $color[4] == $color[5])
		{
			return $color[0] . $color[2] . $color[4];
		}

		return $color;
	}


	/**
	 * Convert hex colors to rgb
	 *
	 * @param string  $color
	 * @param boolean $blnWriteToFile
	 * @param array   $vars
	 *
	 * @return array
	 *
	 * @see http://de3.php.net/manual/de/function.hexdec.php#99478
	 */
	protected function convertHexColor($color, $blnWriteToFile=false, $vars=array())
	{
		// Support global variables
		if (strncmp($color, '$', 1) === 0)
		{
			if (!$blnWriteToFile)
			{
				return array($color);
			}
			else
			{
				$color = str_replace(array_keys($vars), array_values($vars), $color);
			}
		}

		$rgb = array();

		// Try to convert using bitwise operation
		if (strlen($color) == 6)
		{
			$dec = hexdec($color);
			$rgb['red'] = 0xFF & ($dec >> 0x10);
			$rgb['green'] = 0xFF & ($dec >> 0x8);
			$rgb['blue'] = 0xFF & $dec;
		}

		// Shorthand notation
		elseif (strlen($color) == 3)
		{
			$rgb['red'] = hexdec(str_repeat(substr($color, 0, 1), 2));
			$rgb['green'] = hexdec(str_repeat(substr($color, 1, 1), 2));
			$rgb['blue'] = hexdec(str_repeat(substr($color, 2, 1), 2));
		}

		return $rgb;
	}


	/**
	 * Return a form to choose an existing style sheet and import it
	 *
	 * @return string
	 *
	 * @throws \Exception
	 */
	public function importStyleSheet()
	{
		if (\Input::get('key') != 'import')
		{
			return '';
		}

		$this->import('BackendUser', 'User');
		$class = $this->User->uploader;

		// See #4086 and #7046
		if (!class_exists($class) || $class == 'DropZone')
		{
			$class = 'FileUpload';
		}

		/** @var \FileUpload $objUploader */
		$objUploader = new $class();

		// Import CSS
		if (\Input::post('FORM_SUBMIT') == 'tl_style_sheet_import')
		{
			$arrUploaded = $objUploader->uploadTo('system/tmp');

			if (empty($arrUploaded))
			{
				\Message::addError($GLOBALS['TL_LANG']['ERR']['all_fields']);
				$this->reload();
			}

			foreach ($arrUploaded as $strCssFile)
			{
				// Folders cannot be imported
				if (is_dir(TL_ROOT . '/' . $strCssFile))
				{
					\Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['importFolder'], basename($strCssFile)));
					continue;
				}

				$objFile = new \File($strCssFile, true);

				// Check the file extension
				if ($objFile->extension != 'css')
				{
					\Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $objFile->extension));
					continue;
				}

				// Check the file name
				$strName = preg_replace('/\.css$/i', '', basename($strCssFile));
				$strName = $this->checkStyleSheetName($strName);

				// Create the new style sheet
				$objStyleSheet = $this->Database->prepare("INSERT INTO tl_style_sheet (pid, tstamp, name, media) VALUES (?, ?, ?, ?)")
												->execute(\Input::get('id'), time(), $strName, array('all'));

				$insertId = $objStyleSheet->insertId;

				if (!is_numeric($insertId) || $insertId < 0)
				{
					throw new \Exception('Invalid insert ID');
				}

				// Read the file and remove carriage returns
				$strFile = $objFile->getContent();
				$strFile = str_replace("\r", '', $strFile);

				$arrTokens   = array();
				$strBuffer   = '';
				$intSorting  = 0;
				$strComment  = '';
				$strCategory = '';
				$intLength   = strlen($strFile);

				// Tokenize
				for ($i=0; $i<$intLength; $i++)
				{
					$char = $strFile[$i];

					// Whitespace
					if ($char == '' || $char == "\n" || $char == "\t")
					{
						// Ignore
					}

					// Comment
					elseif ($char == '/')
					{
						if ($strFile[$i+1] == '*')
						{
							while ($i<$intLength)
							{
								$strBuffer .= $strFile[$i++];

								if ($strFile[$i] == '/' && $strFile[$i-1] == '*')
								{
									$arrTokens[] = array
									(
										'type'    => 'comment',
										'content' => $strBuffer . $strFile[$i]
									);

									$strBuffer = '';
									break;
								}
							}
						}
					}

					// At block
					elseif ($char == '@')
					{
						$intLevel = 0;
						$strSelector = '';

						while ($i<$intLength)
						{
							$strBuffer .= $strFile[$i++];

							if ($strFile[$i] == '{')
							{
								if (++$intLevel == 1)
								{
									++$i;
									$strSelector = $strBuffer;
									$strBuffer = '';
								}
							}
							elseif ($strFile[$i] == '}')
							{
								if (--$intLevel == 0)
								{
									$arrTokens[] = array
									(
										'type'     => 'atblock',
										'selector' => $strSelector,
										'content'  => $strBuffer
									);

									$strBuffer = '';
									break;
								}
							}
						}
					}

					// Regular block
					else
					{
						$strSelector = '';

						while ($i<$intLength)
						{
							$strBuffer .= $strFile[$i++];

							if ($strFile[$i] == '{')
							{
								++$i;
								$strSelector = $strBuffer;
								$strBuffer = '';
							}
							elseif ($strFile[$i] == '}')
							{
								$arrTokens[] = array
								(
									'type'     => 'block',
									'selector' => $strSelector,
									'content'  => $strBuffer
								);

								$strBuffer = '';
								break;
							}
						}
					}
				}

				foreach ($arrTokens as $arrToken)
				{
					// Comments
					if ($arrToken['type'] == 'comment')
					{
						// Category (comments start with /** and contain only one line)
						if (strncmp($arrToken['content'], '/**', 3) === 0 && substr_count($arrToken['content'], "\n") == 2)
						{
							$strCategory = trim(str_replace(array('/*', '*/', '*'), '', $arrToken['content']));
						}

						// Declaration comment
						elseif (strpos($arrToken['content'], "\n") === false)
						{
							$strComment = trim(str_replace(array('/*', '*/', '*'), '', $arrToken['content']));
						}
					}

					// At blocks like @media or @-webkit-keyframe
					elseif ($arrToken['type'] == 'atblock')
					{
						$arrSet = array
						(
							'pid'        => $insertId,
							'category'   => $strCategory,
							'comment'    => $strComment,
							'sorting'    => $intSorting += 128,
							'selector'   => trim($arrToken['selector']),
							'own'        => $arrToken['content']
						);

						$this->Database->prepare("INSERT INTO tl_style %s")->set($arrSet)->execute();
						$strComment = '';
					}

					// Regular blocks
					else
					{
						$arrDefinition = array
						(
							'pid'        => $insertId,
							'category'   => $strCategory,
							'comment'    => $strComment,
							'sorting'    => $intSorting += 128,
							'selector'   => trim($arrToken['selector']),
							'attributes' => $arrToken['content']
						);

						$this->createDefinition($arrDefinition);
						$strComment = '';
					}
				}

				// Write the style sheet
				$this->updateStyleSheet($insertId);

				// Notify the user
				if ($strName . '.css' != basename($strCssFile))
				{
					\Message::addInfo(sprintf($GLOBALS['TL_LANG']['tl_style_sheet']['css_renamed'], basename($strCssFile), $strName . '.css'));
				}
				else
				{
					\Message::addConfirmation(sprintf($GLOBALS['TL_LANG']['tl_style_sheet']['css_imported'], $strName . '.css'));
				}
			}

			// Redirect
			\System::setCookie('BE_PAGE_OFFSET', 0, 0);
			$this->redirect(str_replace('&key=import', '', \Environment::get('request')));
		}

		// Return form
		return '
<div id="tl_buttons">
<a href="' .ampersand(str_replace('&key=import', '', \Environment::get('request'))). '" class="header_back" title="' .specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']). '" accesskey="b">' .$GLOBALS['TL_LANG']['MSC']['backBT']. '</a>
</div>
' .\Message::generate(). '
<form action="' .ampersand(\Environment::get('request'), true). '" id="tl_style_sheet_import" class="tl_form" method="post" enctype="multipart/form-data">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_style_sheet_import">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">
<input type="hidden" name="MAX_FILE_SIZE" value="'.\Config::get('maxFileSize').'">

<div class="tl_tbox">
  <h3>'.$GLOBALS['TL_LANG']['tl_style_sheet']['source'][0].'</h3>'.$objUploader->generateMarkup().(isset($GLOBALS['TL_LANG']['tl_style_sheet']['source'][1]) ? '
  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['tl_style_sheet']['source'][1].'</p>' : '').'
</div>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
  <input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="' .specialchars($GLOBALS['TL_LANG']['tl_style_sheet']['import'][0]). '">
</div>

</div>
</form>';
	}


	/**
	 * Export a style sheet
	 *
	 * @param \DataContainer $dc
	 *
	 * @throws \Exception
	 */
	public function exportStyleSheet(\DataContainer $dc)
	{
		$objStyleSheet = $this->Database->prepare("SELECT * FROM tl_style_sheet WHERE id=?")
										->limit(1)
										->execute($dc->id);

		if ($objStyleSheet->numRows < 1)
		{
			throw new \Exception("Invalid style sheet ID {$dc->id}");
		}

		$vars = array();

		// Get the global theme variables
		$objTheme = $this->Database->prepare("SELECT vars FROM tl_theme WHERE id=?")
								   ->limit(1)
								   ->execute($objStyleSheet->pid);

		if ($objTheme->vars != '')
		{
			if (is_array(($tmp = deserialize($objTheme->vars))))
			{
				foreach ($tmp as $v)
				{
					$vars[$v['key']] = $v['value'];
				}
			}
		}

		// Merge the global style sheet variables
		if ($objStyleSheet->vars != '')
		{
			if (is_array(($tmp = deserialize($objStyleSheet->vars))))
			{
				foreach ($tmp as $v)
				{
					$vars[$v['key']] = $v['value'];
				}
			}
		}

		// Sort by key length (see #3316)
		uksort($vars, 'length_sort_desc');

		// Create the file
		$objFile = new \File('system/tmp/' . md5(uniqid(mt_rand(), true)), true);
		$objFile->write('');

		// Add the media query (see #7560)
		if ($objStyleSheet->mediaQuery != '')
		{
			$objFile->append($objStyleSheet->mediaQuery . ' {');
		}

		$objDefinitions = $this->Database->prepare("SELECT * FROM tl_style WHERE pid=? AND invisible!='1' ORDER BY sorting")
										 ->execute($objStyleSheet->id);

		// Append the definition
		while ($objDefinitions->next())
		{
			$objFile->append($this->compileDefinition($objDefinitions->row(), false, $vars, $objStyleSheet->row(), true), '');
		}

		// Close the media query
		if ($objStyleSheet->mediaQuery != '')
		{
			$objFile->append('}');
		}

		$objFile->close();
		$objFile->sendToBrowser($objStyleSheet->name . '.css');
		$objFile->delete();
	}


	/**
	 * Check the name of an imported file
	 *
	 * @param string $strName
	 *
	 * @return string
	 */
	public function checkStyleSheetName($strName)
	{
		$objStyleSheet = $this->Database->prepare("SELECT COUNT(*) AS count FROM tl_style_sheet WHERE name=?")
										->limit(1)
										->execute($strName);

		if ($objStyleSheet->count < 1)
		{
			return $strName;
		}

		$chunks = explode('-', $strName);
		$i = (count($chunks) > 1) ? array_pop($chunks) : 0;
		$strName = implode('-', $chunks) . '-' . (intval($i) + 1);

		return $this->checkStyleSheetName($strName);
	}


	/**
	 * Create a format definition and insert it into the database
	 *
	 * @param array $arrDefinition
	 */
	protected function createDefinition($arrDefinition)
	{
		$arrSet = array
		(
			'pid'      => $arrDefinition['pid'],
			'sorting'  => $arrDefinition['sorting'],
			'tstamp'   => time(),
			'comment'  => $arrDefinition['comment'],
			'category' => $arrDefinition['category'],
			'selector' => $arrDefinition['selector']
		);

		$arrAttributes = array_map('trim', explode(';', $arrDefinition['attributes']));

		foreach ($arrAttributes as $strDefinition)
		{
			// Skip empty definitions
			if (trim($strDefinition) == '')
			{
				continue;
			}

			// Handle keywords, variables and functions (see #7448)
			if (strpos($strDefinition, 'important') !== false || strpos($strDefinition, 'transparent') !== false || strpos($strDefinition, 'inherit') !== false || strpos($strDefinition, '$') !== false || strpos($strDefinition, '(') !== false)
			{
				$arrSet['own'][] = $strDefinition;
				continue;
			}

			$arrChunks = array_map('trim', explode(':', $strDefinition, 2));
			$strKey = strtolower($arrChunks[0]);

			switch ($strKey)
			{
				case 'width':
				case 'height':
					if ($arrChunks[1] == 'auto')
					{
						$strUnit = '';
						$varValue = 'auto';
					}
					else
					{
						$strUnit = preg_replace('/[^acehimnprtvwx%]/', '', $arrChunks[1]);
						$varValue = preg_replace('/[^0-9\.-]+/', '', $arrChunks[1]);
					}
					$arrSet['size'] = 1;
					$arrSet[$strKey]['value'] = $varValue;
					$arrSet[$strKey]['unit'] = $strUnit;
					break;

				case 'min-width':
				case 'min-height':
					$strName = str_replace('-', '', $strKey);
					if ($arrChunks[1] == 'inherit')
					{
						$strUnit = '';
						$varValue = 'inherit';
					}
					else
					{
						$strUnit = preg_replace('/[^acehimnprtvwx%]/', '', $arrChunks[1]);
						$varValue = preg_replace('/[^0-9\.-]+/', '', $arrChunks[1]);
					}
					$arrSet['size'] = 1;
					$arrSet[$strName]['value'] = $varValue;
					$arrSet[$strName]['unit'] = $strUnit;
					break;

				case 'max-width':
				case 'max-height':
					$strName = str_replace('-', '', $strKey);
					if ($arrChunks[1] == 'inherit' || $arrChunks[1] == 'none')
					{
						$strUnit = '';
						$varValue = $arrChunks[1];
					}
					else
					{
						$strUnit = preg_replace('/[^acehimnprtvwx%]/', '', $arrChunks[1]);
						$varValue = preg_replace('/[^0-9\.-]+/', '', $arrChunks[1]);
					}
					$arrSet['size'] = 1;
					$arrSet[$strName]['value'] = $varValue;
					$arrSet[$strName]['unit'] = $strUnit;
					break;

				case 'top':
				case 'right':
				case 'bottom':
				case 'left':
					if ($arrChunks[1] == 'auto')
					{
						$strUnit = '';
						$varValue = 'auto';
					}
					elseif (isset($arrSet['trbl']['unit']))
					{
						$arrSet['own'][] = $strDefinition;
						break;
					}
					else
					{
						$strUnit = preg_replace('/[^acehimnprtvwx%]/', '', $arrChunks[1]);
						$varValue = preg_replace('/[^0-9\.-]+/', '', $arrChunks[1]);
					}
					$arrSet['positioning'] = 1;
					$arrSet['trbl'][$strKey] = $varValue;
					if ($strUnit != '')
					{
						$arrSet['trbl']['unit'] = $strUnit;
					}
					break;

				case 'position':
				case 'overflow':
				case 'clear':
				case 'display':
					$arrSet['positioning'] = 1;
					$arrSet[$strKey] = $arrChunks[1];
					break;

				case 'float':
					$arrSet['positioning'] = 1;
					$arrSet['floating'] = $arrChunks[1];
					break;

				case 'margin':
				case 'padding':
					$arrSet['alignment'] = 1;
					$arrTRBL = preg_split('/\s+/', $arrChunks[1]);
					$arrUnits = array();
					switch (count($arrTRBL))
					{
						case 1:
							if ($arrTRBL[0] == 'auto')
							{
								$strUnit = '';
								$varValue = 'auto';
							}
							else
							{
								$strUnit = preg_replace('/[^acehimnprtvwx%]/', '', $arrTRBL[0]);
								$varValue = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[0]);
							}
							$arrSet[$strKey] = array
							(
								'top' => $varValue,
								'right' => $varValue,
								'bottom' => $varValue,
								'left' => $varValue,
								'unit' => $strUnit
							);
							break;

						case 2:
							if ($arrTRBL[0] == 'auto')
							{
								$varValue_1 = 'auto';
							}
							else
							{
								$arrUnits[] = preg_replace('/[^acehimnprtvwx%]/', '', $arrTRBL[0]);
								$varValue_1 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[0]);
							}
							if ($arrTRBL[1] == 'auto')
							{
								$varValue_2 = 'auto';
							}
							else
							{
								$arrUnits[] = preg_replace('/[^acehimnprtvwx%]/', '', $arrTRBL[1]);
								$varValue_2 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[1]);
							}
							// Move to custom section if there are different units
							if (count(array_filter(array_unique($arrUnits))) > 1)
							{
								$arrSet['alignment'] = '';
								$arrSet['own'][] = $strDefinition;
								break;
							}
							$arrSet[$strKey] = array
							(
								'top' => $varValue_1,
								'right' => $varValue_2,
								'bottom' => $varValue_1,
								'left' => $varValue_2,
								'unit' => ''
							);
							// Overwrite the unit
							foreach ($arrUnits as $strUnit)
							{
								if ($strUnit != '')
								{
									$arrSet[$strKey]['unit'] = $strUnit;
									break;
								}
							}
							break;

						case 3:
							if ($arrTRBL[0] == 'auto')
							{
								$varValue_1 = 'auto';
							}
							else
							{
								$arrUnits[] = preg_replace('/[^acehimnprtvwx%]/', '', $arrTRBL[0]);
								$varValue_1 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[0]);
							}
							if ($arrTRBL[1] == 'auto')
							{
								$varValue_2 = 'auto';
							}
							else
							{
								$arrUnits[] = preg_replace('/[^acehimnprtvwx%]/', '', $arrTRBL[1]);
								$varValue_2 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[1]);
							}
							if ($arrTRBL[2] == 'auto')
							{
								$varValue_3 = 'auto';
							}
							else
							{
								$arrUnits[] = preg_replace('/[^acehimnprtvwx%]/', '', $arrTRBL[2]);
								$varValue_3 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[2]);
							}
							// Move to custom section if there are different units
							if (count(array_filter(array_unique($arrUnits))) > 1)
							{
								$arrSet['alignment'] = '';
								$arrSet['own'][] = $strDefinition;
								break;
							}
							$arrSet[$strKey] = array
							(
								'top' => $varValue_1,
								'right' => $varValue_2,
								'bottom' => $varValue_3,
								'left' => $varValue_2,
								'unit' => ''
							);
							// Overwrite the unit
							foreach ($arrUnits as $strUnit)
							{
								if ($strUnit != '')
								{
									$arrSet[$strKey]['unit'] = $strUnit;
									break;
								}
							}
							break;

						case 4:
							if ($arrTRBL[0] == 'auto')
							{
								$varValue_1 = 'auto';
							}
							else
							{
								$arrUnits[] = preg_replace('/[^acehimnprtvwx%]/', '', $arrTRBL[0]);
								$varValue_1 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[0]);
							}
							if ($arrTRBL[1] == 'auto')
							{
								$varValue_2 = 'auto';
							}
							else
							{
								$arrUnits[] = preg_replace('/[^acehimnprtvwx%]/', '', $arrTRBL[1]);
								$varValue_2 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[1]);
							}
							if ($arrTRBL[2] == 'auto')
							{
								$varValue_3 = 'auto';
							}
							else
							{
								$arrUnits[] = preg_replace('/[^acehimnprtvwx%]/', '', $arrTRBL[2]);
								$varValue_3 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[2]);
							}
							if ($arrTRBL[3] == 'auto')
							{
								$varValue_4 = 'auto';
							}
							else
							{
								$arrUnits[] = preg_replace('/[^acehimnprtvwx%]/', '', $arrTRBL[3]);
								$varValue_4 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[3]);
							}
							// Move to custom section if there are different units
							if (count(array_filter(array_unique($arrUnits))) > 1)
							{
								$arrSet['alignment'] = '';
								$arrSet['own'][] = $strDefinition;
								break;
							}
							$arrSet[$strKey] = array
							(
								'top' => $varValue_1,
								'right' => $varValue_2,
								'bottom' => $varValue_3,
								'left' => $varValue_4,
								'unit' => ''
							);
							// Overwrite the unit
							foreach ($arrUnits as $strUnit)
							{
								if ($strUnit != '')
								{
									$arrSet[$strKey]['unit'] = $strUnit;
									break;
								}
							}
							break;
					}
					break;

				case 'margin-top':
				case 'margin-right':
				case 'margin-bottom':
				case 'margin-left':
					$arrSet['alignment'] = 1;
					$strName = str_replace('margin-', '', $strKey);
					if ($arrChunks[1] == 'auto')
					{
						$strUnit = '';
						$varValue = 'auto';
					}
					else
					{
						$strUnit = preg_replace('/[^acehimnprtvwx%]/', '', $arrChunks[1]);
						$varValue = preg_replace('/[^0-9\.-]+/', '', $arrChunks[1]);
					}
					$arrSet['margin'][$strName] = $varValue;
					if (empty($arrSet['margin']['unit']))
					{
						$arrSet['margin']['unit'] = $strUnit;
					}
					break;

				case 'padding-top':
				case 'padding-right':
				case 'padding-bottom':
				case 'padding-left':
					$arrSet['alignment'] = 1;
					$strName = str_replace('padding-', '', $strKey);
					$varValue = preg_replace('/[^0-9\.-]+/', '', $arrChunks[1]);
					$strUnit = preg_replace('/[^acehimnprtvwx%]/', '', $arrChunks[1]);
					$arrSet['padding'][$strName] = $varValue;
					$arrSet['padding']['unit'] = $strUnit;
					break;

				case 'align':
				case 'text-align':
				case 'vertical-align':
				case 'white-space':
					$arrSet['alignment'] = 1;
					$arrSet[str_replace('-', '', $strKey)] = $arrChunks[1];
					break;

				case 'background-color':
					if (!preg_match('/^#[a-f0-9]+$/i', $arrChunks[1]))
					{
						$arrSet['own'][] = $strDefinition;
					}
					else
					{
						$arrSet['background'] = 1;
						$arrSet['bgcolor'] = str_replace('#', '', $arrChunks[1]);
					}
					break;

				case 'background-image':
					$url = preg_replace('/url\(["\']?([^"\'\)]+)["\']?\)/i', '$1', $arrChunks[1]);

					if (strncmp($url, '-', 1) === 0)
					{
						// Ignore vendor prefixed commands
					}
					elseif (strncmp($url, 'radial-gradient', 15) === 0)
					{
						$arrSet['own'][] = $strDefinition; // radial gradients (see #4640)
					}
					else
					{
						$arrSet['background'] = 1;

						// Handle linear gradients (see #4640)
						if (strncmp($url, 'linear-gradient', 15) === 0)
						{
							$colors = trimsplit(',', preg_replace('/linear-gradient ?\(([^\)]+)\)/', '$1', $url));
							$arrSet['gradientAngle'] = array_shift($colors);
							$arrSet['gradientColors'] = serialize($colors);
						}
						else
						{
							$arrSet['bgimage'] = $url;
						}
					}
					break;

				case 'background-position':
					$arrSet['background'] = 1;
					if (preg_match('/[0-9]+/', $arrChunks[1]))
					{
						$arrSet['own'][] = $strDefinition;
					}
					else
					{
						$arrSet['bgposition'] = $arrChunks[1];
					}
					break;

				case 'background-repeat':
					$arrSet['background'] = 1;
					$arrSet['bgrepeat'] = $arrChunks[1];
					break;

				case 'border':
					if ($arrChunks[1] == 'none')
					{
						$arrSet['own'][] = $strDefinition;
						break;
					}
					$arrWSC = preg_split('/\s+/', $arrChunks[1]);
					if ($arrWSC[2] != '' && !preg_match('/^#[a-f0-9]+$/i', $arrWSC[2]))
					{
						$arrSet['own'][] = $strDefinition;
						break;
					}
					$arrSet['border'] = 1;
					$varValue = preg_replace('/[^0-9\.-]+/', '', $arrWSC[0]);
					$strUnit = preg_replace('/[^acehimnprtvwx%]/', '', $arrWSC[0]);
					$arrSet['borderwidth'] = array
					(
						'top' => $varValue,
						'right' => $varValue,
						'bottom' => $varValue,
						'left' => $varValue,
						'unit' => $strUnit
					);
					if ($arrWSC[1] != '')
					{
						$arrSet['borderstyle'] = $arrWSC[1];
					}
					if ($arrWSC[2] != '')
					{
						$arrSet['bordercolor'] = str_replace('#', '', $arrWSC[2]);
					}
					break;

				case 'border-top':
				case 'border-right':
				case 'border-bottom':
				case 'border-left':
					if ($arrChunks[1] == 'none')
					{
						$arrSet['own'][] = $strDefinition;
						break;
					}
					$arrWSC = preg_split('/\s+/', $arrChunks[1]);
					if ($arrWSC[2] != '' && !preg_match('/^#[a-f0-9]+$/i', $arrWSC[2]))
					{
						$arrSet['own'][] = $strDefinition;
						break;
					}
					$arrSet['border'] = 1;
					$strName = str_replace('border-', '', $strKey);
					$varValue = preg_replace('/[^0-9\.-]+/', '', $arrWSC[0]);
					$strUnit = preg_replace('/[^acehimnprtvwx%]/', '', $arrWSC[0]);
					if ((isset($arrSet['borderwidth']['unit']) && $arrSet['borderwidth']['unit'] != $strUnit) || ($arrWSC[1] != '' && isset($arrSet['borderstyle']) && $arrSet['borderstyle'] != $arrWSC[1]) || ($arrWSC[2] != '' && isset($arrSet['bordercolor']) && $arrSet['bordercolor'] != $arrWSC[2]))
					{
						$arrSet['own'][] = $strDefinition;
						break;
					}
					$arrSet['borderwidth'][$strName] = preg_replace('/[^0-9\.-]+/', '', $varValue);
					$arrSet['borderwidth']['unit'] = $strUnit;
					if ($arrWSC[1] != '')
					{
						$arrSet['borderstyle'] = $arrWSC[1];
					}
					if ($arrWSC[2] != '')
					{
						$arrSet['bordercolor'] = str_replace('#', '', $arrWSC[2]);
					}
					break;

				case 'border-width':
					$arrSet['border'] = 1;
					$arrTRBL = preg_split('/\s+/', $arrChunks[1]);
					$strUnit = '';
					foreach ($arrTRBL as $v)
					{
						if ($v != 0)
						{
							$strUnit = preg_replace('/[^acehimnprtvwx%]/', '', $arrTRBL[0]);
						}
					}
					switch (count($arrTRBL))
					{
						case 1:
							$varValue = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[0]);
							$arrSet['borderwidth'] = array
							(
								'top' => $varValue,
								'right' => $varValue,
								'bottom' => $varValue,
								'left' => $varValue,
								'unit' => $strUnit
							);
							break;
						case 2:
							$varValue_1 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[0]);
							$varValue_2 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[1]);
							$arrSet['borderwidth'] = array
							(
								'top' => $varValue_1,
								'right' => $varValue_2,
								'bottom' => $varValue_1,
								'left' => $varValue_2,
								'unit' => $strUnit
							);
							break;
						case 4:
							$arrSet['borderwidth'] = array
							(
								'top' => preg_replace('/[^0-9\.-]+/', '', $arrTRBL[0]),
								'right' => preg_replace('/[^0-9\.-]+/', '', $arrTRBL[1]),
								'bottom' => preg_replace('/[^0-9\.-]+/', '', $arrTRBL[2]),
								'left' => preg_replace('/[^0-9\.-]+/', '', $arrTRBL[3]),
								'unit' => $strUnit
							);
							break;
					}
					break;

				case 'border-color':
					if (!preg_match('/^#[a-f0-9]+$/i', $arrChunks[1]))
					{
						$arrSet['own'][] = $strDefinition;
					}
					else
					{
						$arrSet['border'] = 1;
						$arrSet['bordercolor'] = str_replace('#', '', $arrChunks[1]);
					}
					break;

				case 'border-radius':
					$arrSet['border'] = 1;
					$arrTRBL = preg_split('/\s+/', $arrChunks[1]);
					$strUnit = '';
					foreach ($arrTRBL as $v)
					{
						if ($v != 0)
						{
							$strUnit = preg_replace('/[^acehimnprtvwx%]/', '', $arrTRBL[0]);
						}
					}
					switch (count($arrTRBL))
					{
						case 1:
							$varValue = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[0]);
							$arrSet['borderradius'] = array
							(
								'top' => $varValue,
								'right' => $varValue,
								'bottom' => $varValue,
								'left' => $varValue,
								'unit' => $strUnit
							);
							break;
						case 2:
							$varValue_1 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[0]);
							$varValue_2 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[1]);
							$arrSet['borderradius'] = array
							(
								'top' => $varValue_1,
								'right' => $varValue_2,
								'bottom' => $varValue_1,
								'left' => $varValue_2,
								'unit' => $strUnit
							);
							break;
						case 4:
							$arrSet['borderradius'] = array
							(
								'top' => preg_replace('/[^0-9\.-]+/', '', $arrTRBL[0]),
								'right' => preg_replace('/[^0-9\.-]+/', '', $arrTRBL[1]),
								'bottom' => preg_replace('/[^0-9\.-]+/', '', $arrTRBL[2]),
								'left' => preg_replace('/[^0-9\.-]+/', '', $arrTRBL[3]),
								'unit' => $strUnit
							);
							break;
					}
					break;

				case '-moz-border-radius':
				case '-webkit-border-radius':
					// Ignore
					break;

				case 'border-style':
				case 'border-collapse':
					$arrSet['border'] = 1;
					$arrSet[str_replace('-', '', $strKey)] = $arrChunks[1];
					break;

				case 'border-spacing':
					$arrSet['border'] = 1;
					$arrSet['borderspacing'] = array
					(
						'value' => preg_replace('/[^0-9\.-]+/', '', $arrChunks[1]),
						'unit' => preg_replace('/[^acehimnprtvwx%]/', '', $arrChunks[1])
					);
					break;

				case 'font-family':
					$arrSet['font'] = 1;
					$arrSet[str_replace('-', '', $strKey)] = $arrChunks[1];
					break;

				case 'font-size':
					if (!preg_match('/[0-9]+/', $arrChunks[1]))
					{
						$arrSet['own'][] = $strDefinition;
					}
					else
					{
						$arrSet['font'] = 1;
						$arrSet['fontsize'] = array
						(
							'value' => preg_replace('/[^0-9\.-]+/', '', $arrChunks[1]),
							'unit' => preg_replace('/[^acehimnprtvwx%]/', '', $arrChunks[1])
						);
					}
					break;

				case 'font-weight':
					if ($arrChunks[1] == 'bold' || $arrChunks[1] == 'normal')
					{
						$arrSet['font'] = 1;
						$arrSet['fontstyle'][] = $arrChunks[1];
					}
					else
					{
						$arrSet['own'][] = $strDefinition;
					}
					break;

				case 'font-style':
					if ($arrChunks[1] == 'italic')
					{
						$arrSet['font'] = 1;
						$arrSet['fontstyle'][] = 'italic';
					}
					else
					{
						$arrSet['own'][] = $strDefinition;
					}
					break;

				case 'text-decoration':
					$arrSet['font'] = 1;
					switch ($arrChunks[1])
					{
						case 'underline':
							$arrSet['fontstyle'][] = 'underline';
							break;

						case 'none':
							$arrSet['fontstyle'][] = 'notUnderlined';
							break;

						case 'overline':
						case 'line-through':
							$arrSet['fontstyle'][] = $arrChunks[1];
							break;
					}
					break;

				case 'font-variant':
					$arrSet['font'] = 1;
					if ($arrChunks[1] == 'small-caps')
					{
						$arrSet['fontstyle'][] = 'small-caps';
					}
					else
					{
						$arrSet['own'][] = $strDefinition;
					}
					break;

				case 'color':
					if (!preg_match('/^#[a-f0-9]+$/i', $arrChunks[1]))
					{
						$arrSet['own'][] = $strDefinition;
					}
					else
					{
						$arrSet['font'] = 1;
						$arrSet['fontcolor'] = str_replace('#', '', $arrChunks[1]);
					}
					break;

				case 'line-height':
					$arrSet['font'] = 1;
					$arrSet['lineheight'] = array
					(
						'value' => preg_replace('/[^0-9\.-]+/', '', $arrChunks[1]),
						'unit' => preg_replace('/[^acehimnprtvwx%]/', '', $arrChunks[1])
					);
					break;

				case 'text-transform':
					$arrSet['font'] = 1;
					$arrSet['texttransform'] = $arrChunks[1];
					break;

				case 'text-indent':
				case 'letter-spacing':
				case 'word-spacing':
					$strName = str_replace('-', '', $strKey);
					$arrSet['font'] = 1;
					$arrSet[$strName] = array
					(
						'value' => preg_replace('/[^0-9\.-]+/', '', $arrChunks[1]),
						'unit' => preg_replace('/[^acehimnprtvwx%]/', '', $arrChunks[1])
					);
					break;

				case 'list-style-type':
					$arrSet['list'] = 1;
					$arrSet[str_replace('-', '', $strKey)] = $arrChunks[1];
					break;

				case 'list-style-image':
					$arrSet['list'] = 1;
					$arrSet['liststyleimage'] = preg_replace('/url\(["\']?([^"\'\)]+)["\']?\)/i', '$1', $arrChunks[1]);
					break;

				case 'behavior':
					if ($arrChunks[1] != 'url(\'assets/' . $GLOBALS['TL_ASSETS']['CSS3PIE'] . '/css3pie/PIE.htc\')')
					{
						$arrSet['own'][] = $strDefinition;
					}
					break;

				default:
					$blnIsOwn = true;

					// Allow custom definitions
					if (isset($GLOBALS['TL_HOOKS']['createDefinition']) && is_array($GLOBALS['TL_HOOKS']['createDefinition']))
					{
						foreach ($GLOBALS['TL_HOOKS']['createDefinition'] as $callback)
						{
							$this->import($callback[0]);
							$arrTemp = $this->$callback[0]->$callback[1]($strKey, $arrChunks[1], $strDefinition, $arrSet);

							if ($arrTemp && is_array($arrTemp))
							{
								$blnIsOwn = false;
								$arrSet = array_merge($arrSet, $arrTemp);
							}
						}
					}

					// Unknown definition
					if ($blnIsOwn)
					{
						$arrSet['own'][] = $strDefinition;
					}
					break;
			}
		}

		if (!empty($arrSet['own']))
		{
			$arrSet['own'] = implode(";\n", $arrSet['own']) . ';';
		}

		$this->Database->prepare("INSERT INTO tl_style %s")->set($arrSet)->execute();
	}


	/**
	 * Return an image as data: string
	 *
	 * @param string $strImage
	 * @param array  $arrParent
	 *
	 * @return string|boolean
	 */
	protected function generateBase64Image($strImage, $arrParent)
	{
		if ($arrParent['embedImages'] > 0 && file_exists(TL_ROOT . '/' . $strImage))
		{
			$objImage = new \File($strImage, true);
			$strExtension = $objImage->extension;

			// Fix the jpg mime type
			if ($strExtension == 'jpg')
			{
				$strExtension = 'jpeg';
			}

			// Return the data: string
			if ($objImage->size <= $arrParent['embedImages'])
			{
				return 'data:image/' . $strExtension . ';base64,' . base64_encode($objImage->getContent());
			}
		}

		return false;
	}
}
