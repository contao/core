<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class StyleSheets
 *
 * Provide methods to handle style sheets.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class StyleSheets extends Backend
{

	/**
	 * Import String library
	 */
	public function __construct()
	{
		parent::__construct();

		$this->import('String');
		$this->import('Files');
	}


	/**
	 * Update a particular style sheet
	 * @param integer
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
		if ($this->Input->get('act') == 'delete')
		{
			$this->import('Files');
			$this->Files->delete('system/scripts/' . $objStyleSheet->name . '.css');
		}

		// Update the CSS file
		else
		{
			$this->writeStyleSheet($objStyleSheet->row());
			$this->log('Generated style sheet "' . $objStyleSheet->name . '.css"', 'StyleSheets updateStyleSheet()', TL_CRON);
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
		@include(TL_ROOT . '/system/config/dcaconfig.php');

		// Delete old style sheets
		foreach (scan(TL_ROOT . '/system/scripts', true) as $file)
		{
			// Skip directories
			if (is_dir(TL_ROOT . '/system/scripts/' . $file))
			{
				continue;
			}

			// Preserve root files (is this still required now that scripts are in system/scripts?)
			if (is_array($GLOBALS['TL_CONFIG']['rootFiles']) && in_array($file, $GLOBALS['TL_CONFIG']['rootFiles']))
			{
				continue;
			}

			// Do not delete the combined files (see #3605)
			if (preg_match('/^[a-f0-9]{12}\.css$/', $file))
			{
				continue;
			}

			$objFile = new File('system/scripts/' . $file);

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
			$this->log('Generated style sheet "' . $objStyleSheets->name . '.css"', 'StyleSheets updateStyleSheets()', TL_CRON);
		}
	}


	/**
	 * Write a style sheet to a file
	 * @param array
	 */
	protected function writeStyleSheet($row)
	{
		if ($row['id'] == '' || $row['name'] == '')
		{
			return;
		}

		$row['name'] = basename($row['name']);

		// Check whether the target file is writeable
		if (file_exists(TL_ROOT . '/system/scripts/' . $row['name'] . '.css') && !$this->Files->is_writeable('system/scripts/' . $row['name'] . '.css'))
		{
			$this->addErrorMessage(sprintf($GLOBALS['TL_LANG']['ERR']['notWriteable'], 'system/scripts/' . $row['name'] . '.css'));
			return;
		}

		$intCount = 0;
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

		$objFile = new File('system/scripts/' . $row['name'] . '.css');
		$objFile->write('/* Style sheet ' . $row['name'] . " */\n");

		$objDefinitions = $this->Database->prepare("SELECT * FROM tl_style WHERE pid=? AND invisible!=1 ORDER BY sorting")
										 ->execute($row['id']);

		// Append the definition
		while ($objDefinitions->next())
		{
			$objFile->append($this->compileDefinition($objDefinitions->row(), true, $vars), '');
		}

		$objFile->close();
	}


	/**
	 * Compile format definitions and return them as string
	 * @param array
	 * @param boolean
	 * @param array
	 * @return string
	 */
	public function compileDefinition($row, $blnWriteToFile=false, $vars=array())
	{
		$blnDebug = $GLOBALS['TL_CONFIG']['debugMode'];

		if ($blnWriteToFile)
		{
			$strGlue = '../../';
			$lb = ($blnDebug ? "\n    " : '');
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
		if (!$blnWriteToFile && $row['comment'] != '')
		{
			$search = array('@^\s*/\*+@', '@\*+/\s*$@');
			$comment = preg_replace($search, '', $row['comment']);
			$comment = wordwrap(trim($comment), 72);
			$return .= "\n/* " . $comment . " */\n";
		}

		// Selector
		$arrSelector = trimsplit(',', $this->String->decodeEntities($row['selector']));
		$return .= implode(($blnWriteToFile ? ',' : ",\n"), $arrSelector) . ($blnWriteToFile ? ($blnDebug ? ' ' : '') : "\n") . '{';

		// Size
		if ($row['size'])
		{
			// Width
			$row['width'] = deserialize($row['width']);

			if ($row['width']['value'] != '')
			{
				$return .= $lb . 'width:' . $row['width']['value'] . (($row['width']['value'] == 'auto') ? '' : $row['width']['unit']) . ';';
			}

			// Height
			$row['height'] = deserialize($row['height']);

			if ($row['height']['value'] != '')
			{
				$return .= $lb . 'height:' . $row['height']['value'] . (($row['height']['value'] == 'auto') ? '' : $row['height']['unit']) . ';';
			}

			// Min-width
			$row['minwidth'] = deserialize($row['minwidth']);

			if ($row['minwidth']['value'] != '')
			{
				$return .= $lb . 'min-width:' . $row['minwidth']['value'] . $row['minwidth']['unit'] . ';';
			}

			// Min-height
			$row['minheight'] = deserialize($row['minheight']);

			if ($row['minheight']['value'] != '')
			{
				$return .= $lb . 'min-height:' . $row['minheight']['value'] . $row['minheight']['unit'] . ';';
			}

			// Max-width
			$row['maxwidth'] = deserialize($row['maxwidth']);

			if ($row['maxwidth']['value'] != '')
			{
				$return .= $lb . 'max-width:' . $row['maxwidth']['value'] . $row['maxwidth']['unit'] . ';';
			}

			// Max-height
			$row['maxheight'] = deserialize($row['maxheight']);

			if ($row['maxheight']['value'] != '')
			{
				$return .= $lb . 'max-height:' . $row['maxheight']['value'] . $row['maxheight']['unit'] . ';';
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

			// Text alignment
			if ($row['textalign'] != '')
			{
				$return .= $lb . 'text-align:' . $row['textalign'] . ';';
			}

			// Vertical alignment
			if ($row['verticalalign'] != '')
			{
				$return .= $lb . 'vertical-align:' . $row['verticalalign'] . ';';
			}
		}

		// Background
		if ($row['background'])
		{
			$bgColor = deserialize($row['bgcolor'], true);

			// Try to shorten the definition
			if ($row['bgimage'] != '' && $row['bgposition'] != '' && $row['bgrepeat'] != '')
			{
				$glue = (strncmp($row['bgimage'], 'data:', 5) !== 0 && strncmp($row['bgimage'], '/', 1) !== 0) ? $strGlue : '';
				$return .= $lb . 'background:' . (($bgColor[0] != '') ? $this->compileColor($bgColor, $blnWriteToFile, $vars) . ' ' : '') . 'url("' . $glue . $row['bgimage'] . '") ' . $row['bgposition'] . ' ' . $row['bgrepeat'] . ';';
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
					$glue = (strncmp($row['bgimage'], 'data:', 5) !== 0 && strncmp($row['bgimage'], '/', 1) !== 0) ? $strGlue : '';
					$return .= $lb . 'background-image:url("' . $glue . $row['bgimage'] . '");';
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
						$glue = (strncmp($row['bgimage'], 'data:', 5) !== 0 && strncmp($row['bgimage'], '/', 1) !== 0) ? $strGlue : '';
						$bgImage = 'url("' . $glue . $row['bgimage'] . '") ' . $row['bgposition'] . ' ' . $row['bgrepeat'] . ',';
					}

					// Default starting point
					if ($row['gradientAngle'] == '')
					{
						$row['gradientAngle'] = 'top';
					}

					$webkitAngle = $row['gradientAngle'];

					// Convert the starting point to degrees
					$arrMapper = array
					(
						'left'         => '0deg',
						'top'          => '270deg',
						'right'        => '180deg',
						'bottom'       => '90deg',
						'top left'     => '315deg', 
						'left top'     => '315deg', 
						'bottom left'  => '45deg', 
						'left bottom'  => '45deg', 
						'top right'    => '225deg', 
						'right top'    => '225deg', 
						'bottom right' => '135deg', 
						'right bottom' => '135deg' 
					);

					if (isset($arrMapper[$webkitAngle]))
					{
						$webkitAngle = $arrMapper[$webkitAngle];
					}

					$angle = floatval($webkitAngle);
					$multi = 50 / 45; // 45 degree == 50 %

					// Make angle a positive value
					while ($angle < 0)
					{
						$angle += 360;
					}

					// Convert the angle to points in percentage from the top left corner 
					if ($angle >= 0 && $angle < 45)
					{
						$offset = round(($angle * $multi), 2);
						$webkitAngle = '0% ' . (50 + $offset) . '%,100% ' . (50 - $offset) .'%';
					}
					elseif ($angle >= 45 && $angle < 135)
					{
						$offset = round((($angle - 45) * $multi), 2);
						$webkitAngle = $offset . '% 100%,' . (100 - $offset) .'% 0%';
					}
					elseif ($angle >= 135 && $angle < 225)
					{
						$offset = round((($angle - 135) * $multi), 2);
						$webkitAngle = '100% ' . (100 - $offset) . '%,0% ' . $offset .'%';
					}
					elseif ($angle >= 225 && $angle < 315)
					{
						$offset = round((($angle - 225) * $multi), 2);
						$webkitAngle = (100 - $offset) . '% 0%,' . $offset .'% 100%';
					}
					elseif ($angle >= 315 && $angle <= 360)
					{
						$offset = round((($angle - 315) * $multi), 2);
						$webkitAngle = '0% ' . $offset . '%,100% ' . (100 - $offset) .'%';
					}

					$row['gradientColors'] = array_values(array_filter($row['gradientColors']));

					// Add a hash tag to the color values
					foreach ($row['gradientColors'] as $k=>$v)
					{
						$row['gradientColors'][$k] = '#' . $v;
					}

					$webkitColors = $row['gradientColors'];

					// Convert #ffc 10% to color-stop(0.1,#ffc)
					foreach ($webkitColors as $k=>$v)
					{
						// Split #ffc 10%
						list($col, $pct) = explode(' ', $v, 2);

						// Convert 10% to 0.1
						if ($pct != '')
						{
							$pct = intval($pct) / 100;
						}
						else
						{
							// Default values: 0, 0.33, 0.66, 1
							switch ($k)
							{
								case 0:
									$pct = 0;
									break;

								case 1:
									if (count($webkitColors) == 2)
									{
										$pct = 1;
									}
									elseif (count($webkitColors) == 3)
									{
										$pct = 0.5;
									}
									elseif (count($webkitColors) == 4)
									{
										$pct = 0.33;
									}
									break;

								case 2:
									if (count($webkitColors) == 3)
									{
										$pct = 1;
									}
									elseif (count($webkitColors) == 4)
									{
										$pct = 0.66;
									}
									break;

								case 3:
									$pct = 1;
									break;
							}
						}

						// The syntax is: color-stop(0.1,#ffc)
						$webkitColors[$k] = 'color-stop(' . $pct . ',' . $col . ')';
					}

					$gradient = $row['gradientAngle'] . ',' . implode(',', $row['gradientColors']);
					$webkitGradient = $webkitAngle . ',' . implode(',', $webkitColors);

					$return .= $lb . 'background:' . $bgImage . '-moz-linear-gradient(' . $gradient . ');';
					$return .= $lb . 'background:' . $bgImage . '-webkit-gradient(linear,' . $webkitGradient . ');';
					$return .= $lb . 'background:' . $bgImage . '-o-linear-gradient(' . $gradient . ');';
					$return .= $lb . 'background:' . $bgImage . 'linear-gradient(' . $gradient . ');';
					$return .= $lb . '-pie-background:' . $bgImage . 'linear-gradient(' . $gradient . ');';
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
					
					$return .= $lb . '-moz-box-shadow:' . $shadow;
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

						$return .= $lb . '-moz-border-radius:' . $borderradius;
						$return .= $lb . '-webkit-border-radius:' . $borderradius;
						$return .= $lb . 'border-radius:' . $borderradius;
					}
					else
					{
						$arrDir = array('top-left'=>$top, 'top-right'=>$right, 'bottom-right'=>$bottom, 'bottom-left'=>$left);

						foreach ($arrDir as $k=>$v)
						{
							if ($v != '')
							{
								$return .= $lb . '-moz-border-radius-' . str_replace('-', '', $k) . ':' . $v . (($v === '0') ? '' : $row['borderradius']['unit']) . ';';
								$return .= $lb . '-webkit-border-' . $k . '-radius:' . $v . (($v === '0') ? '' : $row['borderradius']['unit']) . ';';
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

			if ($row['borderspacing']['value'] != '')
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
			if ($row['fontfamily'] != '' && $row['fontfamily'] != 'inherit' && $row['fontsize']['value'] != '' && $row['fontsize']['value'] != 'inherit')
			{
				$return .= $lb . 'font:' . $row['fontsize']['value'] . $row['fontsize']['unit'] . (($row['lineheight']['value'] != '') ? '/' . $row['lineheight']['value'] . $row['lineheight']['unit'] : '') . ' ' . $row['fontfamily'] . ';';
			}
			else
			{
				// Font family
				if ($row['fontfamily'] != '')
				{
					$return .= $lb . 'font-family:' . $row['fontfamily'] . ';';
				}

				// Font size
				if ($row['fontsize']['value'] != '')
				{
					$return .= $lb . 'font-size:' . $row['fontsize']['value'] . $row['fontsize']['unit'] . ';';
				}

				// Line height
				if ($row['lineheight']['value'] != '')
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

			// White space
			if ($row['whitespace'] != '')
			{
				$return .= $lb . 'white-space:nowrap;';
			}

			// Text transform
			if ($row['texttransform'] != '')
			{
				$return .= $lb . 'text-transform:' . $row['texttransform'] . ';';
			}

			// Text indent
			$row['textindent'] = deserialize($row['textindent']);

			if ($row['textindent']['value'] != '')
			{
				$return .= $lb . 'text-indent:' . $row['textindent']['value'] . $row['textindent']['unit'] . ';';
			}

			// Letter spacing
			$row['letterspacing'] = deserialize($row['letterspacing']);

			if ($row['letterspacing']['value'] != '')
			{
				$return .= $lb . 'letter-spacing:' . $row['letterspacing']['value'] . $row['letterspacing']['unit'] . ';';
			}

			// Word spacing
			$row['wordspacing'] = deserialize($row['wordspacing']);

			if ($row['wordspacing']['value'] != '')
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
				$glue = (strncmp($row['bgimage'], 'data:', 5) !== 0 && strncmp($row['liststyleimage'], '/', 1) !== 0) ? $strGlue : '';
				$return .= $lb . 'list-style-image:url("' . $glue . $row['liststyleimage'] . '");';
			}
		}

		// CSS3PIE
		if ($blnNeedsPie)
		{
			$return .= $lb . 'behavior:url("plugins/css3pie/PIE.htc");';
		}

		// Custom code
		if ($row['own'] != '')
		{
			$own = trim($this->String->decodeEntities($row['own']));
			$own = preg_replace('/url\("(?!data:)([^\/])/', 'url("' . $strGlue . "$1", $own);
			$own = preg_split('/[\n\r]+/i', $own);
			$return .= $lb . implode(($blnWriteToFile ? '' : $lb), $own);
		}

		// Allow custom definitions
		if (isset($GLOBALS['TL_HOOKS']['compileDefinition']) && is_array($GLOBALS['TL_HOOKS']['compileDefinition']))
		{
			foreach ($GLOBALS['TL_HOOKS']['compileDefinition'] as $callback)
            {                
				$this->import($callback[0]);
				$strTemp = $this->$callback[0]->$callback[1]($row, $blnWriteToFile, $vars);

				if ($strTemp != '')
				{
					$return .= $lb . $strTemp;
				}
			}    
		}

		// Close the format definition
		if ($blnWriteToFile)
		{
			$nl = $blnDebug ? "\n" : '';
			$return = substr($return, 0, -1);
			$return .= $nl . '}' . $nl;
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

		return $return;
	}


	/**
	 * Compile a color value and return a hex or rgba color
	 * @param mixed
	 * @param boolean
	 * @param array
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
	 * @param string
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
	 * @param string
	 * @param boolean
	 * @param array
	 * @return array
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
	 * @return string
	 */
	public function importStyleSheet()
	{
		if ($this->Input->get('key') != 'import')
		{
			return '';
		}

		// Import CSS
		if ($this->Input->post('FORM_SUBMIT') == 'tl_style_sheet_import')
		{
			if (!$this->Input->post('source') || !is_array($this->Input->post('source')))
			{
				$this->addErrorMessage($GLOBALS['TL_LANG']['ERR']['all_fields']);
				$this->reload();
			}

			foreach ($this->Input->post('source') as $strCssFile)
			{
				// Folders cannot be imported
				if (is_dir(TL_ROOT . '/' . $strCssFile))
				{
					$this->addErrorMessage(sprintf($GLOBALS['TL_LANG']['ERR']['importFolder'], basename($strCssFile)));
					continue;
				}

				$objFile = new File($strCssFile);

				// Check the file extension
				if ($objFile->extension != 'css')
				{
					$this->addErrorMessage(sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $objFile->extension));
					continue;
				}

				$strFile = $objFile->getContent();
				$strFile = str_replace("\r", '', $strFile);
				$strName = preg_replace('/\.css$/i', '', basename($strCssFile));
				$strName = $this->checkStyleSheetName($strName);

				// Create the new style sheet
				$objStyleSheet = $this->Database->prepare("INSERT INTO tl_style_sheet (pid, tstamp, name, media) VALUES (?, ?, ?, ?)")
												->execute($this->Input->get('id'), time(), $strName, array('all'));

				$insertId = $objStyleSheet->insertId;
				$intSorting = 0;
				$strComment = '';
				$strCategory = '';
				$arrChunks = array();

				if (!is_numeric($insertId) || $insertId < 0)
				{
					throw new Exception('Invalid insert ID');
				}

				$strFile = str_replace('/**/', '[__]', $strFile);
				$strFile = preg_replace
				(
					array
					(
						'/\/\*\*\n( *\*.*\n){2,} *\*\//', // see #2974
						'/\/\*[^\*]+\{[^\}]+\}[^\*]+\*\//' // see #3478
					),
					'', $strFile
				);

				$arrChunks = preg_split('/\{([^\}]*)\}|\*\//U', $strFile, -1, PREG_SPLIT_DELIM_CAPTURE);

				for ($i=0; $i<count($arrChunks); $i++)
				{
					$strChunk = trim($arrChunks[$i]);

					if ($strChunk == '')
					{
						continue;
					}

					$strChunk = preg_replace('/[\n\r\t]+/', ' ', $strChunk);

					// Category
					if (strncmp($strChunk, '/**', 3) === 0)
					{
						$strCategory = str_replace(array('/*', '*/', '*', '[__]'), '', $strChunk);
						$strCategory = trim(preg_replace('/\s+/', ' ', $strCategory));
					}

					// Comment
					elseif (strncmp($strChunk, '/*', 2) === 0)
					{
						$strComment = str_replace(array('/*', '*/', '*', '[__]'), '', $strChunk);
						$strComment = trim(preg_replace('/\s+/', ' ', $strComment));
					}

					// Format definition
					else
					{
						$strNext = trim($arrChunks[$i+1]);
						$strNext = preg_replace('/[\n\r\t]+/', ' ', $strNext);

						$arrDefinition = array
						(
							'pid' => $insertId,
							'category' => $strCategory,
							'comment' => $strComment,
							'sorting' => $intSorting += 128,
							'selector' => $strChunk,
							'attributes' => $strNext
						);

						$this->createDefinition($arrDefinition);

						++$i;
						$strComment = '';
					}
				}

				// Write the style sheet
				$this->updateStyleSheet($insertId);

				// Notify the user
				if ($strName . '.css' != basename($strCssFile))
				{
					$this->addInfoMessage(sprintf($GLOBALS['TL_LANG']['tl_style_sheet']['css_renamed'], basename($strCssFile), $strName . '.css'));
				}
				else
				{
					$this->addConfirmationMessage(sprintf($GLOBALS['TL_LANG']['tl_style_sheet']['css_imported'], $strName . '.css'));
				}
			}

			// Redirect
			setcookie('BE_PAGE_OFFSET', 0, 0, '/');
			$this->redirect(str_replace('&key=import', '', $this->Environment->request));
		}

		$objTree = new FileTree($this->prepareForWidget($GLOBALS['TL_DCA']['tl_style_sheet']['fields']['source'], 'source', null, 'source', 'tl_style_sheet'));

		// Return form
		return '
<div id="tl_buttons">
<a href="' .ampersand(str_replace('&key=import', '', $this->Environment->request)). '" class="header_back" title="' .specialchars($GLOBALS['TL_LANG']['MSC']['backBT']). '" accesskey="b">' .$GLOBALS['TL_LANG']['MSC']['backBT']. '</a>
</div>

<h2 class="sub_headline">' .$GLOBALS['TL_LANG']['tl_style_sheet']['import'][1]. '</h2>
' .$this->getMessages(). '
<form action="' .ampersand($this->Environment->request, true). '" id="tl_style_sheet_import" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_style_sheet_import">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">

<div class="tl_tbox">
  <h3><label for="source">' .$GLOBALS['TL_LANG']['tl_style_sheet']['source'][0]. '</label> <a href="contao/files.php" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['fileManager']) . '" data-lightbox="files 765 80%">' . $this->generateImage('filemanager.gif', $GLOBALS['TL_LANG']['MSC']['fileManager'], 'style="vertical-align:text-bottom"') . '</a></h3>' .$objTree->generate().(strlen($GLOBALS['TL_LANG']['tl_style_sheet']['source'][1]) ? '
  <p class="tl_help tl_tip">' .$GLOBALS['TL_LANG']['tl_style_sheet']['source'][1]. '</p>' : ''). '
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
	 * Check the name of an imported file
	 * @param string
	 * @return string
	 */
	public function checkStyleSheetName($strName)
	{
		$objStyleSheet = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_style_sheet WHERE name=?")
										->limit(1)
										->execute($strName);

		if ($objStyleSheet->total < 1)
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
	 * @param array
	 */
	protected function createDefinition($arrDefinition)
	{
		$arrSet = array
		(
			'pid' => $arrDefinition['pid'],
			'sorting' => $arrDefinition['sorting'],
			'tstamp' => time(),
			'comment' => $arrDefinition['comment'],
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

			// Handle important definitions
			if (strpos($strDefinition, 'important') !== false || strpos($strDefinition, 'transparent') !== false || strpos($strDefinition, 'inherit') !== false)
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
				case 'min-width':
				case 'min-height':
				case 'max-width':
				case 'max-height':
					$strName = str_replace('-', '', $strKey);
					if ($arrChunks[1] == 'auto')
					{
						$strUnit = '';
						$varValue = 'auto';
					}
					else
					{
						$strUnit = preg_replace('/[^ceimnptx%]/', '', $arrChunks[1]);
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
						$strUnit = preg_replace('/[^ceimnptx%]/', '', $arrChunks[1]);
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
								$strUnit = preg_replace('/[^ceimnptx%]/', '', $arrTRBL[0]);
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
								$arrUnits[] = preg_replace('/[^ceimnptx%]/', '', $arrTRBL[0]);
								$varValue_1 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[0]);
							}
							if ($arrTRBL[1] == 'auto')
							{
								$varValue_2 = 'auto';
							}
							else
							{
								$arrUnits[] = preg_replace('/[^ceimnptx%]/', '', $arrTRBL[1]);
								$varValue_2 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[1]);
							}
							// Move to custom section if there are different units
							if (count(array_filter(array_unique($arrUnits))) > 0)
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
								$arrUnits[] = preg_replace('/[^ceimnptx%]/', '', $arrTRBL[0]);
								$varValue_1 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[0]);
							}
							if ($arrTRBL[1] == 'auto')
							{
								$varValue_2 = 'auto';
							}
							else
							{
								$arrUnits[] = preg_replace('/[^ceimnptx%]/', '', $arrTRBL[1]);
								$varValue_2 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[1]);
							}
							if ($arrTRBL[2] == 'auto')
							{
								$varValue_3 = 'auto';
							}
							else
							{
								$arrUnits[] = preg_replace('/[^ceimnptx%]/', '', $arrTRBL[2]);
								$varValue_3 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[2]);
							}
							// Move to custom section if there are different units
							if (count(array_filter(array_unique($arrUnits))) > 0)
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
								$arrUnits[] = preg_replace('/[^ceimnptx%]/', '', $arrTRBL[0]);
								$varValue_1 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[0]);
							}
							if ($arrTRBL[1] == 'auto')
							{
								$varValue_2 = 'auto';
							}
							else
							{
								$arrUnits[] = preg_replace('/[^ceimnptx%]/', '', $arrTRBL[1]);
								$varValue_2 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[1]);
							}
							if ($arrTRBL[2] == 'auto')
							{
								$varValue_3 = 'auto';
							}
							else
							{
								$arrUnits[] = preg_replace('/[^ceimnptx%]/', '', $arrTRBL[2]);
								$varValue_3 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[2]);
							}
							if ($arrTRBL[3] == 'auto')
							{
								$varValue_4 = 'auto';
							}
							else
							{
								$arrUnits[] = preg_replace('/[^ceimnptx%]/', '', $arrTRBL[3]);
								$varValue_4 = preg_replace('/[^0-9\.-]+/', '', $arrTRBL[3]);
							}
							// Move to custom section if there are different units
							if (count(array_filter(array_unique($arrUnits))) > 0)
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
						$strUnit = preg_replace('/[^ceimnptx%]/', '', $arrChunks[1]);
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
					$strUnit = preg_replace('/[^ceimnptx%]/', '', $arrChunks[1]);
					$arrSet['padding'][$strName] = $varValue;
					$arrSet['padding']['unit'] = $strUnit;
					break;

				case 'align':
				case 'text-align':
				case 'vertical-align':
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
					$arrSet['background'] = 1;
					$arrSet['bgimage'] = preg_replace('/url\(["\']?([^"\'\)]+)["\']?\)/i', '$1', $arrChunks[1]);
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
					$arrSet['border'] = 1;
					$arrWSC = preg_split('/\s+/', $arrChunks[1]);
					$varValue = preg_replace('/[^0-9\.-]+/', '', $arrWSC[0]);
					$strUnit = preg_replace('/[^ceimnptx%]/', '', $arrWSC[0]);
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
					$arrSet['border'] = 1;
					$arrWSC = preg_split('/\s+/', $arrChunks[1]);
					$strName = str_replace('border-', '', $strKey);
					$varValue = preg_replace('/[^0-9\.-]+/', '', $arrWSC[0]);
					$strUnit = preg_replace('/[^ceimnptx%]/', '', $arrWSC[0]);
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
							$strUnit = preg_replace('/[^ceimnptx%]/', '', $arrTRBL[0]);
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
					$arrSet['border'] = 1;
					$arrSet['bordercolor'] = str_replace('#', '', $arrChunks[1]);
					break;

				case 'border-radius':
					$arrSet['border'] = 1;
					$arrTRBL = preg_split('/\s+/', $arrChunks[1]);
					$strUnit = '';
					foreach ($arrTRBL as $v)
					{
						if ($v != 0)
						{
							$strUnit = preg_replace('/[^ceimnptx%]/', '', $arrTRBL[0]);
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
						'unit' => preg_replace('/[^ceimnptx%]/', '', $arrChunks[1])
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
							'unit' => preg_replace('/[^ceimnptx%]/', '', $arrChunks[1])
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
						'unit' => preg_replace('/[^ceimnptx%]/', '', $arrChunks[1])
					);
					break;

				case 'white-space':
					$arrSet['font'] = 1;
					$arrSet['whitespace'] = ($arrChunks[1] == 'nowrap') ? 1 : '';
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
						'unit' => preg_replace('/[^ceimnptx%]/', '', $arrChunks[1])
					);
					break;

				case 'list-style-type':
					$arrSet['list'] = 1;
					$arrSet[str_replace('-', '', $strKey)] = $arrChunks[1];
					break;

				case 'list-style-image':
					$arrSet['list'] = 1;
					$arrSet['liststyleimage'] = preg_replace('/url\("?([^"\)]+)"?\)/i', '$1', $arrChunks[1]);
					break;

				case 'behavior':
					if ($arrChunks[1] != 'url("plugins/css3pie/PIE.htc")')
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
}

?>